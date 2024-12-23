<?php

namespace App\Http\Controllers\ApproverController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController\Approvals\ApplicationForLeave;
use App\Http\Controllers\EmployeeController\Request\Leave;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeLeaveRequest as EmployeeLeaveRequest;
use App\Models\HrisGroupApprover;
use App\Models\HrisGroupApproverNotification;
use App\Services\Reusable\GroupApproverNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LeaveRequest extends Controller
{
    public function index($id = null, $key = null)
    {

        return view('approver._layout.app');
    }

    public function isUrlValid($rq)
    {
        $query = HrisGroupApproverNotification::where([['request_link_token',$rq->param]])->first();
        if(!$query){
            return [
                'status' => 'error',
                'message'=>'Link is invalid, Login to view all requests !',
                'payload'=>'error',
            ];
        }

        $now = Carbon::now();
        if ( ($now->greaterThan($query->link_expired_at) || $query->link_status ==2) || ($query->link == 1 || $query->link == 2) ){
            return [
                'status' => 'error',
                'message' => 'The link has expired or you already approve/reject the request !',
                'payload'=>'expired',
            ];
        }
        return $query;
    }

    public function info(Request $rq)
    {
        try{
            $data = self::isUrlValid($rq);
            if (isset($data['status']) && $data['status'] === 'error') {
                return response()->json($data);
            }

            $approver = HrisGroupApprover::where([['emp_id',$data->emp_id],['group_id',$data->group_id],['is_active',1]])->first();
            if(!$approver){
                return response()->json([
                    'status' => 'error',
                    'message'=>'You are not eligible for approving',
                    'payload'=>'error'
                ]);
            }

            $view = self::modal($data);
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode($view)]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function next_request(Request $rq)
    {
        try{
            DB::beginTransaction();

            $data = HrisGroupApproverNotification::where([['request_link_token',$rq->param]])->first();
            if(!$data){
                return response()->json([
                    'status' => 'error',
                    'message'=>'Link is invalid, Login to view all requests !',
                    'payload'=>'error',
                ]);
            }

            $query = HrisGroupApproverNotification::where([['emp_id',$data->emp_id],['is_approved',null],['entity_table',2]])->first();
            if($query){
                $isApprover = HrisGroupApprover::where([['emp_id',$query->emp_id],['group_id',$query->group_id],['is_active',1]])->exists();
                if(!$isApprover){
                    return response()->json([
                        'status' => 'error',
                        'message'=>'You are not eligible for approving',
                        'payload'=>'error'
                    ]);
                }
                $view = self::modal($query);
            }else{
                $view = self::modal(false);
            }

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>[
                'view'=>base64_encode($view),
                'token'=>$query->request_link_token ?? false
            ]]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function modal($data)
    {
        $isCurrentApprover = false;
        if($data && $data->id){
            $query = EmployeeLeaveRequest::find($data->entity_id);
            $isCurrentApprover = (new Leave)->isApprovingOpen($data->emp_id,$query->id,$query->group_member->group_id);
            $latestApproval = $query->latest_approval_histories;
            $data = [
                'encrypted_id' =>Crypt::encrypt($query->id),
                'requestor' =>$query->employee->fullname(),
                'leave_filing_date' =>Carbon::parse($query->leave_filing_date)->format('F j, Y'),
                'leave_date_from' =>Carbon::parse($query->leave_date_from)->format('F j, Y'),
                'leave_date_to' =>Carbon::parse($query->leave_date_to)->format('F j, Y'),
                'reason' =>$query->reason,
                'is_approved'=>$query->is_approved,
                'approver_remarks'=> $latestApproval? $latestApproval->approver_remarks : '--',
                'approver'=> $latestApproval?$latestApproval->employee->fullname() :false,
                'is_required'=> $data->is_required,
            ];
        }
        return view('approver.leave_request',compact('data','isCurrentApprover'))->render();
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $leaveRequest = EmployeeLeaveRequest::with('employee_position')->find($id);
            if(!$leaveRequest){
                return response()->json(['status' => 'error','message'=>'Leave Request Not Found']);
            }elseif($leaveRequest->is_approved ==1) {
                return response()->json(['status' => 'error','message'=>'Request is already approved']);
            }elseif($leaveRequest->is_approved ==2) {
                return response()->json(['status' => 'error','message'=>'Request is already rejected']);
            }

            $data = self::isUrlValid($rq);
            if (isset($data['status']) && $data['status'] === 'error') {
                return response()->json($data);
            }

            $isCurrentApprover = (new ApplicationForLeave())->isApprovingOpen($data->emp_id,$leaveRequest->id,$leaveRequest->group_member->group_id);
            if(!$isCurrentApprover){
                return response()->json(['status' => 'error','message'=>'You are not the current Approver']);
            }

            $approver = HrisGroupApprover::where([['emp_id',$data->emp_id],['group_id',$data->group_id],['is_active',1]])->first();
            if(!$approver){
                return response()->json([
                    'status' => 'error',
                    'message'=>'You are not eligible for approving',
                    'payload'=>'error'
                ]);
            }

            HrisApprovalHistory::create([
                'entity_id'=>$leaveRequest->id,
                'entity_table'=>2,
                'emp_id'=>$data->emp_id,
                'is_approved'=>$rq->is_approved,
                'approver_level'=>$approver->approver_level,
                'is_final_approver'=>$approver->is_final_approver,
                'is_required'=>$approver->is_required,
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$data->emp_id,
            ]);

            if($approver->is_final_approver != 1 && $rq->is_approved != 2){
                $isNotified = (new GroupApproverNotification)
                ->sendApprovalNotification($leaveRequest,2,'approver.leave_request',$approver->approver_level);
                if(!$isNotified){
                    DB::rollback();
                    return response()->json(['status' => 'error','message'=>'Something went wrong, try again later']);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message'=>'Leave Request is updated',
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
