<?php

namespace App\Http\Controllers\EmployeeController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisApprovingOfficer;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisGroupApprover;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OvertimeRequisition extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;

        $filter_group = $rq->filter_group ?? false;

        $emp_id = Auth::user()->emp_id;
        $group_id = HrisGroupApprover::where([['emp_id',$emp_id],['is_active',1]])->pluck('group_id');

        $data = HrisEmployeeOvertimeRequest::with(['group_member','latest_approval_histories','employee','employee_position'])
        ->when($filter_status, function ($q) use ($filter_status) {
            $status = [
                'pending'=>null,
                'approved'=>1,
                'disapproved'=>2,
            ];
            $q->where('is_approved', $status[$filter_status]);
        })
        ->when($filter_month, fn($q) =>
            $q->whereRaw('MONTH(overtime_date) = ?', [$filter_month])
        )
        ->when($filter_year, fn($q) =>
            $q->whereRaw('YEAR(overtime_date) = ?', [$filter_year])
        )
        ->whereHas('group_member',function($q) use($group_id){
            $q->whereIn('group_id',$group_id);
        })
        ->where([['emp_id','!=',$emp_id],['is_deleted',null]])
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) use($emp_id) {

            $approver = $item->latest_approval_histories;
            $approved_by = null;
            $approver_level = null;
            $approver_remarks = null;
            $approver_status = null;
            $approver_type = null;
            if($approver){
                $approved_by = $approver->employee->fullname();
                $approver_level = 'Level '.$approver->approver_level;
                $approver_remarks = $approver->approver_remarks;
                $approver_status = $approver->is_final_approver || $approver->is_approved == 2 ? $approver->is_approved:'Waiting for next approver';
                $approver_type = $approver->approver_type == 1?'Department-Level':'Section-Level';
            }

            $item->count = $key + 1;
            $item->requestor = $item->employee->fullname();

            $item->overtime_date = Carbon::parse($item->overtime_date)->format('m/d/Y');
            $item->overtime_from = Carbon::parse($item->overtime_from)->format('h:i A');
            $item->overtime_to = Carbon::parse($item->overtime_to)->format('h:i A');

            $item->is_current_approver = self::isApprovingOpen($emp_id,$item->id,$item->group_member->group_id);
            $item->approver_status = $approver_status;
            $item->approver_type = $approver_type;

            $item->reason_short = str_word_count($item->reason, 0) > 6
            ? implode(' ', array_slice(explode(' ', $item->reason), 0, 6)) . '...'
            : null;

            $item->approved_by = $approved_by;
            $item->approver_level = $approver_level;
            $item->approver_remarks = $approver_remarks;
            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });

        $table = new DTServerSide($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' =>  $table->getRecordsFiltered(),
            'data' => $table->getRows()
        ]);
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;

            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):false;
            if(!$id)
            {
                return response()->json(['status' => 'error','message'=>'Missing ID in Request']);
            }

            $overtimeRequest = HrisEmployeeOvertimeRequest::with('employee_position')->find($id);
            if(!$overtimeRequest)
            {
                return response()->json(['status' => 'error','message'=>'Overtime Request Not Found']);
            }

            $approvingOfficer = HrisGroupApprover::where([
                ['emp_id',$user_id],
                ['group_id',$overtimeRequest->group_member->group_id],
                ['is_active',1]
            ])->first();
            if(!$approvingOfficer)
            {
                return response()->json(['status' => 'error','message'=>'You are not eligible for approving request']);
            }

            HrisApprovalHistory::create([
                'entity_id'=>$id,
                'entity_table'=>1,
                'emp_id'=>$user_id,
                'is_approved'=>$rq->is_approved,
                'approver_level'=>$approvingOfficer->approver_level,
                'is_final_approver'=>$approvingOfficer->is_final_approver,
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$user_id,
            ]);

            DB::commit();
            return response()->json(['status' => 'info','message'=>'Overtime Request is updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function isApprovingOpen($user_id,$overtime_id,$group_id)
    {
        try{
            //check if there is already approving history of the employee and if approve return false
            $approvingHistory = self::checkIfAlreadyApproved($user_id,$overtime_id);
            if ($approvingHistory) {
                return false;
            }
            //check if final approver already approve
            $checkFinalApprover = self::checkFinalApprover($overtime_id);
            if ($checkFinalApprover) {
                return false;
            }

            // Check if we found a valid approver
            $currentApprover = self::checkCurrentApprover($overtime_id,$group_id);
            if (!$currentApprover) {
                return false;
            }

            // Check if there are any required lower levels pending approval
            $requiredLowerLevels = self::checkRequiredLowerLevels($overtime_id,$group_id,$currentApprover);
            if ($requiredLowerLevels) {
                return false;
            }

            // Allow approval if the current approver is the logged-in user
            return $currentApprover->emp_id == $user_id || $currentApprover->is_required == null;

        }catch(Exception $e)
        {
            return false;
        }
    }

    public function checkFinalApprover($overtime_id)
    {
        return HrisApprovalHistory::where([
            ['entity_id', $overtime_id],
            ['entity_table', 1],
            ['is_approved', 1],
            ['is_final_approver', 1],
        ])->exists();
    }

    public function checkIfAlreadyApproved($user_id,$overtime_id)
    {
        //check if there is already approving history of the employee and if approve return false
        return  HrisApprovalHistory::where([
            ['entity_id', $overtime_id],
            ['emp_id', $user_id],
            ['entity_table', 1],
            ['is_approved', 1],
        ])->exists();
    }

    public function checkCurrentApprover($overtime_id,$group_id)
    {
        return HrisGroupApprover::where([['is_active', 1],['group_id',$group_id]])
        ->whereDoesntHave('approving_history', function ($query) use ($overtime_id) {
            $query->where([
                ['entity_id', $overtime_id],
                ['entity_table', 1],
                ['is_approved', 1], // Exclude approvers who have already approved
            ]);
        })
        ->orderBy('is_final_approver', 'ASC')
        ->orderBy('approver_level', 'DESC') // Process highest levels first
         // Non-final approvers first
        ->first();

    }

    public function checkRequiredLowerLevels($overtime_id,$group_id,$currentApprover)
    {
        $lowestApprover = HrisGroupApprover::where([['group_id',$group_id],['is_active',1]])
        ->orderBy('is_final_approver', 'ASC')->orderBy('approver_level', 'DESC')->first();

        $userApproverLevel = HrisGroupApprover::where([['group_id',$group_id],['emp_id',Auth::user()->emp_id],['is_active',1]])->first();
        if($userApproverLevel->approver_level == $lowestApprover->approver_level)
        {
            return false;
        }

        $pendingApprover = HrisGroupApprover::where([
            ['is_active', 1],
            ['group_id', $group_id],
            ['id','!=', $currentApprover->id],
            ['is_required', 1],
        ])
        ->whereDoesntHave('approving_history', function ($query) use ($overtime_id) {
            $query->where([
                ['entity_id', $overtime_id],
                ['entity_table', 1],
                ['is_approved', 1], // Check for approvals
            ]);
        })
        ->orderBy('is_final_approver', 'ASC')
        ->orderBy('approver_level', 'DESC')
        ->first();

        // If there's no pending required lower approver, return false
        if (!$pendingApprover) {
            return false;
        }

        // If the pending approver is the current user, they can approve
        if($pendingApprover->emp_id == Auth::user()->emp_id){
            return false;
        }

        return true;
    }

}
