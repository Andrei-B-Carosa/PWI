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
        if ($now->greaterThan($query->link_expired_at) || ($query->link == 1 || $query->link == 2)){
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

            $query = HrisGroupApproverNotification::where([['emp_id',$data->emp_id],['is_approved',null],['entity_table',3]])->latest()->first();
            if($query){
                $view = self::modal($query);
            }else{
                $view = self::modal(false);
            }

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode($view)]);
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
            $query = OBRequest::find($data->entity_id);
            $isCurrentApprover = (new OfficialBusiness)->isApprovingOpen($data->emp_id,$query->id,$query->group_member->group_id);
            $data = [
                'encrypted_id' =>Crypt::encrypt($query->id),
                'requestor' =>$query->employee->fullname(),
                'ob_filing_date' =>Carbon::parse($query->ob_filing_date)->format('F j, Y'),
                'ob_time_out' =>Carbon::parse($query->ob_time_out)->format('h:ia'),
                'ob_time_in' =>Carbon::parse($query->ob_time_in)->format('h:ia'),
                'destination' =>$query->destination,
                'purpose' =>$query->purpose,
                'contact_person_id' =>optional($query->emp_contact_person)->fullname() ?? null,
                'is_approved'=>$query->is_approved,
                'ob_duration_hours' => Carbon::parse($query->ob_time_out)->diffInHours(Carbon::parse($query->ob_time_in)),
            ];
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
                return response()->json(['status' => 'error','message'=>'Overtime Request Not Found']);
            }elseif($obRequest->is_approved ==1 || $obRequest->is_approved ==2) {
                return response()->json(['status' => 'error','message'=>'Something went wrong, try again later']);
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
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$data->emp_id,
            ]);

            if($approver->is_final_approver != 1 && $rq->is_approved != 2){
                $isNotified = (new GroupApproverNotification)
                ->sendApprovalNotification($obRequest,3,'approver.ob_request',$approver->approver_level);
                if(!$isNotified){
                    DB::rollback();
                    return response()->json(['status' => 'error','message'=>'Something went wrong, try again later']);
                }
            }
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message'=>'OB Request is updated',
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
