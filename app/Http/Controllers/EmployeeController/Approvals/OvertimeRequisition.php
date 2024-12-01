<?php

namespace App\Http\Controllers\EmployeeController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisApprovingOfficer;
use App\Models\HrisEmployeeOvertimeRequest;
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
        $filter_department = isset($rq->filter_department) ? Crypt::decrypt($rq->filter_department) : false;
        $departmentIds = [];
        $user = Auth::user();

        if(!$filter_department){
            $checkApprover = HrisApprovingOfficer::where([['emp_id',$user->emp_id],['is_active',1],['is_deleted',null]])->get();
            if($checkApprover){
                $departmentIds = $checkApprover->pluck('department_id')->toArray();
            }
        }

        $ApproverIds = HrisApprovingOfficer::where([['is_active',1],['is_deleted',null]])->pluck('emp_id');
        $data = HrisEmployeeOvertimeRequest::with(['latest_approval_histories','employee','employee_position'])
        ->where([['emp_id','!=',$user->emp_id],['is_deleted',null]])
        ->when($filter_department, fn($q) =>
            $q->whereHas('employee_position',fn($q) =>
                $q->where('department_id',$filter_department)
            )
        )
        ->when(!$filter_department && $departmentIds, fn($q) =>
        $q->whereHas('employee_position',fn($q) =>
                $q->whereIn('department_id',$departmentIds)
            )
        )
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
        ->whereNotIn('emp_id',$ApproverIds)
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) use($user) {

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

            $department_id = $item->employee_position->department_id;

            $item->count = $key + 1;
            $item->overtime_date = Carbon::parse($item->overtime_date)->format('m/d/Y');
            $item->overtime_from = Carbon::parse($item->overtime_from)->format('h:i A');
            $item->overtime_to = Carbon::parse($item->overtime_to)->format('h:i A');
            $item->requestor = $item->employee->fullname();
            $item->position_name = $item->employee_position->position->name;
            $item->is_current_approver = self::isApprovingOpen($user->emp_id,$item->id,$department_id);
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

            $approvingOfficer = HrisApprovingOfficer::where([
                ['emp_id',$user_id],
                ['department_id',$overtimeRequest->employee_position->department_id],
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

    public function isApprovingOpen($user_id,$overtime_id,$dept_id)
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
            $currentApprover = self::checkCurrentApprover($overtime_id,$dept_id);
            if (!$currentApprover) {
                return false;
            }


            // Check if there are any required lower levels pending approval
            $requiredLowerLevels = self::checkRequiredLowerLevels($overtime_id,$dept_id,$currentApprover);
            // if($overtime_id ==3)
            //     dd($requiredLowerLevels);
            if ($requiredLowerLevels) {
                return false;
            }

            // Allow approval if the current approver is the logged-in user or optional
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

    public function checkCurrentApprover($overtime_id,$dept_id)
    {
        return HrisApprovingOfficer::where([
            ['is_active', 1], // Ensure the approver is active
            ['department_id', $dept_id], // Match department
        ])
        ->whereDoesntHave('approving_history', function ($query) use ($overtime_id) {
            $query->where([
                ['entity_id', $overtime_id],
                ['entity_table', 1],
                ['is_approved', 1], // Exclude approvers who have already approved
            ]);
        })
        ->orderBy('approver_level', 'DESC') // Process highest levels first
        ->orderBy('is_final_approver', 'ASC') // Non-final approvers first
        ->first();

    }

    public function checkRequiredLowerLevels($overtime_id,$dept_id,$currentApprover)
    {
        return HrisApprovingOfficer::where([
            ['is_active', 1],
            ['department_id', $dept_id],
            ['is_required', 1], // Required levels only
        ])
        ->where('approver_level', '>', $currentApprover->approver_level) // Levels below the current one
        ->whereDoesntHave('approving_history', function ($query) use ($overtime_id) {
            $query->where([
                ['entity_id', $overtime_id],
                ['entity_table', 1],
                ['is_approved', 1], // Check for approvals
            ]);
        })
        ->exists();
    }

}
