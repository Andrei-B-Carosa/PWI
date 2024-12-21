<?php

namespace App\Http\Controllers\ApproverController;

use App\Http\Controllers\Controller;
use App\Models\EmployeeAccount;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisGroupApprover;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OvertimeRequest extends Controller
{
    public function index($id = null, $key = null)
    {
        return view('approver._layout.app');
    }

    public function isUrlValid($rq)
    {
        $data = Crypt::decrypt($rq->param);
        if (!isset($data['id'], $data['expiration'])) {
            return [
                'status' => 'error',
                'message'=>'Request not found, Redirecting you . . .',
                'payload'=>'expired',
            ];
        }

        $isKeyValid = EmployeeAccount::where([['bypass_key',$rq->key],['is_active',1]])->exists();
        if(!$isKeyValid){
            return [
                'status' => 'error',
                'message'=>'You are not eligible for approving, Redirecting you . . .',
                'payload'=>'invalid',
            ];
        }

        return $data;
    }

    public function info(Request $rq)
    {
        try{

            $data = self::isUrlValid($rq);
            if (isset($data['status']) && $data['status'] === 'error') {
                return response()->json($data);
            }

            $isLinkValid = Carbon::now()->timestamp > $data['expiration']? false:true;
            $view = self::modal($data['id'],$isLinkValid);

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode($view)]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }

    }

    public function next_request(Request $rq)
    {
        try{
            DB::beginTransaction();

            $validate = self::isUrlValid($rq);
            if (isset($validate['status']) && $validate['status'] === 'error') {
                return response()->json($validate);
            }

            $approver = EmployeeAccount::where([['bypass_key',$rq->key],['is_active',1]])->first();
            if(!$approver){
                return response()->json(['status' => 'error','message'=>'You are not eligible for approving']);
            }

            $ApproversGroupIds = HrisGroupApprover::where([['emp_id',$approver->emp_id],['is_active',1]])->pluck('group_id');
            $data = HrisEmployeeOvertimeRequest::with(['latest_approval_histories','employee','employee_position'])
            ->whereHas('group_member',fn($q) => $q->whereIn('group_id',$ApproversGroupIds))
            ->where([['emp_id','!=',$approver->emp_id],['is_deleted',null],['is_approved',null]])
            ->orderBy('id', 'DESC')
            ->first();
            if($data){
                $view = self::modal($data->id,true);
            }else{
                $view = self::modal(false,false);
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

    public function modal($id,$isLinkValid)
    {
        $data = [];
        if($id != false){
            $query = HrisEmployeeOvertimeRequest::find($id);
            $data = [
                'encrypted_id' =>Crypt::encrypt($query->id),
                'requestor' =>$query->employee->fullname(),
                'is_approved'=>$query->is_approved,

                'overtime_date' =>Carbon::parse($query->overtime_date)->format('F j, Y'),
                'overtime_from' =>Carbon::parse($query->overtime_from)->format('h:ia'),
                'overtime_to' =>Carbon::parse($query->overtime_to)->format('h:ia'),
                'reason' =>$query->reason,
                'is_approved'=>$query->is_approved,
            ];
        }
        return view('approver.ot_request',compact('data','isLinkValid'))->render();
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $otRequest = HrisEmployeeOvertimeRequest::with('employee_position')->find($id);
            if(!$otRequest)
            {
                return response()->json(['status' => 'error','message'=>'Overtime Request Not Found']);
            }

            $approver = EmployeeAccount::where([['bypass_key',$rq->key],['is_active',1]])->first();
            if(!$approver){
                return response()->json(['status' => 'error','message'=>'You are not eligible for approving']);
            }

            $approvingOfficer = HrisGroupApprover::where([
                ['emp_id',$approver->emp_id],
                ['group_id',$otRequest->group_member->group_id],
                ['is_active',1]
            ])->first();
            if(!$approvingOfficer)
            {
                return response()->json(['status' => 'error','message'=>'You are not eligible for approving request']);
            }

            HrisApprovalHistory::create([
                'entity_id'=>$otRequest->id,
                'entity_table'=>1,
                'emp_id'=>$approver->emp_id,
                'is_approved'=>$rq->is_approved,
                'approver_level'=>$approvingOfficer->approver_level,
                'is_final_approver'=>$approvingOfficer->is_final_approver,
                'approver_remarks'=>$rq->approver_remarks,
                'created_by'=>$approver->emp_id,
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'OB Request is updated']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
