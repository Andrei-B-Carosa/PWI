<?php

namespace App\Http\Controllers\EmployeeController\PersonnelMonitoring;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Models\HrisEmployeePosition;
use App\Models\HrisGroupApprover;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;


class OfficialBusinessForm extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;
        $filter_group = isset($rq->filter_group) ? Crypt::decrypt($rq->filter_group) : false;

        $emp_id = Auth::user()->emp_id;

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

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeOfficialBusinessRequest::find($id);

            $payload = [
                'ob_time_out' =>$query->actual_ob_time_out?Carbon::parse($query->actual_ob_time_out)->format('H:i'):null,
                'ob_time_in' =>$query->actual_ob_time_in?Carbon::parse($query->actual_ob_time_in)->format('H:i'):null,
                'guard_remarks' =>$query->guard_remarks,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
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

            $obTimeOut = $rq->actual_ob_time_out?Carbon::createFromFormat('H:i', $rq->actual_ob_time_out)->format('H:i') :null;
            $obTimeIn = $rq->actual_ob_time_in?Carbon::createFromFormat('H:i', $rq->actual_ob_time_in)->format('H:i'):null;

            $obRequest = HrisEmployeeOfficialBusinessRequest::with('employee_position')->find($id);
            if($obRequest->actual_ob_time_out){
                $obTimeOut = $obRequest->actual_ob_time_out;
            }
            $obRequest->guard_id = $user_id;
            $obRequest->guard_remarks = $rq->guard_remarks;
            $obRequest->actual_ob_time_in = $obTimeIn;
            $obRequest->actual_ob_time_out = $obTimeOut;
            $obRequest->save();

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
                    $action = '<span class="text-success fw-bold">Approved</span>'.' the ob request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the ob request';
                        $is_approved = 'rejected';
                    }

                    $history[]=[
                        'is_approved' => $is_approved,
                        'action' => $data->employee->fullname().' '.$action,
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
}
