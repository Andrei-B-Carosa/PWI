<?php

namespace App\Http\Controllers\EmployeeController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisApprovalHistory;
use App\Models\HrisApprovingOfficer;
use App\Models\HrisEmployeeLeaveRequest;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Models\HrisEmployeePosition;
use App\Models\HrisGroupApprover;
use App\Services\Reusable\DTServerSide;
use App\Services\Reusable\GroupApproverNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OfficialBusiness extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;
        $filter_group = isset($rq->filter_group) ? Crypt::decrypt($rq->filter_group) : false;

        $emp_id = Auth::user()->emp_id;

        // $ApproverIds = HrisGroupApprover::where([['is_active',1],['is_deleted',null]])->pluck('emp_id');
        $ApproversGroupIds = HrisGroupApprover::where([['emp_id',$emp_id],['is_active',1]])->pluck('group_id');
        $isGuard = HrisEmployeePosition::where([['emp_id',Auth::user()->emp_id],['is_active',1]])
        ->whereHas('position', function ($q) {
            $q->whereRaw('LOWER(name) = ?', ['guard']);
        })
        ->exists();

        $data = HrisEmployeeOfficialBusinessRequest::with(['latest_approval_histories','employee','employee_position'])
        ->when($filter_group, fn($q) =>
            $q->whereHas('group_member',fn($q) =>
                $q->where('group_id',$filter_group)
            )
        )
        ->when(!$filter_group && !$isGuard, fn($q) =>
            $q->whereHas('group_member',fn($q) =>
                $q->whereIn('group_id',$ApproversGroupIds)
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
        // ->whereNotIn('emp_id',$ApproverIds)
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

            if($item->latest_approval_histories){
                $approved_by = $approver->employee->fullname();
                $approver_level = 'Level '.$approver->approver_level;
                $approver_remarks = $approver->approver_remarks;
                $approver_status = $approver->is_final_approver || $approver->is_approved == 2 ? $approver->is_approved:'Waiting for next approver';
                $approver_type = $approver->approver_type == 1?'Department-Level':'Section-Level';
            }

            $item->count = $key + 1;
            $item->ob_filing_date = Carbon::parse($item->ob_filing_date)->format('m/d/Y');
            $item->ob_time_out = Carbon::parse($item->ob_time_out)->format('h:i A');
            $item->ob_time_in = Carbon::parse($item->ob_time_in)->format('h:i A');

            $item->requestor = $item->employee->fullname();
            $item->group_name = $item->group_member->group->name;

            $item->is_current_approver = self::isApprovingOpen($emp_id,$item->id,$item->group_member->group_id);
            $item->approver_status = $approver_status;
            $item->approver_type = $approver_type;

            $item->reason = $item->purpose;
            $item->reason_short = str_word_count($item->purpose, 0) > 6
            ? implode(' ', array_slice(explode(' ', $item->purpose), 0, 6)) . '...'
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

            $obRequest = HrisEmployeeOfficialBusinessRequest::with('employee_position')->find($id);
            if(!$obRequest)
            {
                return response()->json(['status' => 'error','message'=>'OB Request Not Found']);
            }

            $approvingOfficer = HrisGroupApprover::where([
                ['emp_id',$user_id],
                ['group_id',$obRequest->group_member->group_id],
                ['is_active',1]
            ])->first();
            if(!$approvingOfficer)
            {
                return response()->json(['status' => 'error','message'=>'You are not eligible for approving request']);
            }

            HrisApprovalHistory::create([
                'entity_id'=>$id,
                'entity_table'=>3,
                'emp_id'=>$user_id,
                'is_approved'=>$rq->is_approved,
                'approver_level'=>$approvingOfficer->approver_level,
                'is_final_approver'=>$approvingOfficer->is_final_approver,
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$user_id,
            ]);

            $isNotified = true;
            if($approvingOfficer->is_final_approver != 1 && $rq->is_approved != 2){
                $isNotified = (new GroupApproverNotification)
                ->sendApprovalNotification($obRequest,3,'approver.ob_request',false);
            }

            if($isNotified){
                DB::commit();
                return response()->json(['status' => 'success','message'=>'OB Request is updated']);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message'=>'Something went wrong, try again later',
                    // 'message' => $e->getMessage(),
                ]);
            }
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function view_history(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;

            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):false;
            if(!$id)
            {
                return response()->json(['status' => 'error','message'=>'Missing ID in Request']);
            }

            $obRequest = HrisEmployeeOfficialBusinessRequest::find($id);
            $obRequestHistory = HrisApprovalHistory::with('employee')->where([['entity_id',$id],['entity_table',3]])->get();
            if(!$obRequestHistory)
            {
                return response()->json(['status' => 'error','message'=>'OB Request Not Found']);
            }

            $history[] = [
                'is_approved' => 'pending',
                'action' => $obRequest->employee->fullname().' filed a OB request',
                'recorded_at'  => Carbon::parse($obRequest->created_at)->format('M d, Y H:i A')
            ];

            if($obRequestHistory->isNotEmpty())
            {
                foreach($obRequestHistory as $data){
                    $action = ' <span class="text-success fw-bold">Approved</span>'.' the ob request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the ob request';
                        $is_approved = 'rejected';
                    }

                    if($data->emp_id == $user_id){
                        $action = $data->employee->fullname().' (You) '.$action;
                    }else{
                        $action = $data->employee->fullname().$action;
                    }

                    $history[]=[
                        'is_approved' => $is_approved,
                        'action' => $action,
                        'approver_remarks' =>$approver_remarks,
                        'approver_level' =>$approver_level,
                        'is_final_approver' =>$is_final_approver,
                        'recorded_at' =>Carbon::parse($data->created_at)->format('M d, Y H:i A')
                    ];
                }
            }

            $payload =base64_encode(json_encode($history));
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>$payload]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function emp_details(Request $rq)
    {
        try{
            DB::beginTransaction();
            $id =  Crypt::decrypt($rq->id);

            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):false;
            if(!$id)
            {
                return response()->json(['status' => 'error','message'=>'Missing ID in Request']);
            }

            $query = HrisEmployeeOfficialBusinessRequest::with('employee_position')->find($id);
            if(!$query)
            {
                return response()->json(['status' => 'error','message'=>'OB Request Not Found']);
            }

            $employee = $query->employee;
            $payload = [
                'name'=>$employee->fullname(),
                'position'=>$employee->emp_details->position->name,
                'position'=>$employee->emp_details->department->name,
            ];
            return response()->json([
                'status' => 'success',
                'message'=>'success',
                'payload' => base64_encode(json_encode($payload))
            ]);

        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function isApprovingOpen($user_id,$ob_id,$group_id)
    {
        try{
            //check if there is already approving history of the employee and if approve return false
            $approvingHistory = self::checkIfAlreadyApproved($user_id,$ob_id);
            if ($approvingHistory) {
                return false;
            }

            //check if final approver already approve
            $checkFinalApprover = self::checkFinalApprover($ob_id);
            if ($checkFinalApprover) {
                return false;
            }

            // Check if we found a valid approver
            $currentApprover = self::checkCurrentApprover($ob_id,$group_id);
            if (!$currentApprover) {
                return false;
            }

            // Check if there are any required lower levels pending approval
            $requiredLowerLevels = self::checkRequiredLowerLevels($ob_id,$group_id,$currentApprover,$user_id);
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

    public function checkFinalApprover($ob_id)
    {
        return HrisApprovalHistory::where([
            ['entity_id', $ob_id],
            ['entity_table', 3],
            ['is_approved', 1],
            ['is_final_approver', 1],
        ])->exists();
    }

    public function checkIfAlreadyApproved($user_id,$ob_id)
    {
        //check if there is already approving history of the employee and if approve return false
        return  HrisApprovalHistory::where([
            ['entity_id', $ob_id],
            ['emp_id', $user_id],
            ['entity_table', 3],
            ['is_approved', 1],
        ])->exists();
    }

    public function checkCurrentApprover($ob_id,$group_id)
    {
        return HrisGroupApprover::where([['is_active', 1],['group_id',$group_id]])
        ->whereDoesntHave('approving_history', function ($query) use ($ob_id) {
            $query->where([
                ['entity_id', $ob_id],
                ['entity_table', 3],
                ['is_approved', 1], // Exclude approvers who have already approved
            ]);
        })
        ->orderBy('is_final_approver', 'ASC') // Non-final approvers first
        ->orderBy('approver_level', 'DESC') // Process highest levels first
        ->first();

    }

    public function checkRequiredLowerLevels($ob_id,$group_id,$currentApprover,$user_id)
    {
        if($currentApprover->is_required){
            return false;
        }

        $lowestApprover = HrisGroupApprover::where([['group_id',$group_id],['is_active',1]])
        ->orderBy('is_final_approver', 'ASC')->orderBy('approver_level', 'DESC')->first();

        $userApproverLevel = HrisGroupApprover::where([['group_id',$group_id],['emp_id',$user_id],['is_active',1]])->first();
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
        ->whereDoesntHave('approving_history', function ($query) use ($ob_id) {
            $query->where([
                ['entity_id', $ob_id],
                ['entity_table', 3],
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
        if($pendingApprover->emp_id == $user_id){
            return false;
        }

        return true;
    }
}
