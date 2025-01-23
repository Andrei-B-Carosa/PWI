<?php

namespace App\Http\Controllers\ApproverController;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmployeeController\Approvals\OfficialBusiness;
use App\Models\EmployeeAccount;
use App\Models\HrisApprovalHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\HrisEmployeeOfficialBusinessRequest as OBRequest;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisGroupApprover;
use App\Models\HrisGroupApproverNotification;
use App\Services\Reusable\GroupApproverNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfficialBusinessRequest extends Controller
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
        if ($now->greaterThan($query->link_expired_at)){
            return [
                'status' => 'error',
                'message' => 'The link has already expired !',
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

            $query = HrisGroupApproverNotification::where([['emp_id',$data->emp_id],['is_approved',null],['entity_table',3]])->first();
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

    public function modal($notificationData)
    {
        $isCurrentApprover = false;  $data = [];
        if($notificationData && $notificationData->id){
            $query = OBRequest::find($notificationData->entity_id);
            $isCurrentApprover = (new OfficialBusiness)->isApprovingOpen($notificationData->emp_id,$query->id,$query->group_member->group_id);
            $latestApproval = $query->latest_approval_histories;

            $data = [
                'is_approved' => $query->is_approved,
                'approver_remarks'=> $latestApproval? $latestApproval->approver_remarks : '--',
                'is_required' => $notificationData->is_required,
                'approver'=> $latestApproval?$latestApproval->employee->fullname() :false,
                'link_status'=>$notificationData->link_status,
            ];
            if($notificationData && $notificationData->link_status == 2){
                $data['is_approved'] = $notificationData->is_approved;
                $data['approver_remarks'] =$notificationData->approver_remarks;
                $data['approver'] = $notificationData->employee->fullname();
                $isCurrentApprover = false;
            }
            $data = array_merge($data,[
                'encrypted_id' =>Crypt::encrypt($query->id),
                'requestor' =>$query->employee->fullname(),
                'ob_filing_date' =>Carbon::parse($query->ob_filing_date)->format('F j, Y'),
                'ob_time_out' =>Carbon::parse($query->ob_time_out)->format('h:ia'),
                'ob_time_in' =>Carbon::parse($query->ob_time_in)->format('h:ia'),
                'destination' =>$query->destination,
                'purpose' =>$query->purpose,
                'contact_person_id' =>optional($query->emp_contact_person)->fullname() ?? null,
            ]);
        }
        return view('approver.ob_request',compact('data','isCurrentApprover'))->render();
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $obRequest = HrisEmployeeOfficialBusinessRequest::with('employee_position')->find($id);
            if(!$obRequest){
                return response()->json(['status' => 'error','message'=>'OB Request Not Found']);
            }
            if($obRequest->is_approved ==1 || $obRequest->is_approved ==2) {
                return response()->json(['status' => 'error','message'=>'Request is already approved/rejected']);
            }

            $data = self::isUrlValid($rq);
            if (isset($data['status']) && $data['status'] === 'error') {
                return response()->json($data);
            }

            $isCurrentApprover = (new OfficialBusiness)->isApprovingOpen($data->emp_id,$obRequest->id,$obRequest->group_member->group_id);
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
                'entity_id'=>$obRequest->id,
                'entity_table'=>3,
                'emp_id'=>$data->emp_id,
                'is_approved'=>$rq->is_approved,
                'approver_level'=>$approver->approver_level,
                'is_final_approver'=>$approver->is_final_approver,
                'is_required'=>$approver->is_required,
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$data->emp_id,
            ]);

            $isNotified = true;
            if($approver->is_final_approver != 1 && $rq->is_approved != 2){
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
}
