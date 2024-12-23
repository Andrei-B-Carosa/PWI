<?php

namespace App\Http\Controllers\EmployeeController\Request;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeLeaveRequest;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Services\Reusable\DTServerSide;
use App\Services\Reusable\GroupApproverNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OverTime extends Controller
{
    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;

        $data = HrisEmployeeOvertimeRequest::with('latest_approval_histories')
        ->where([['emp_id',$emp_id],['is_deleted',null]])
        ->when($filter_status, function ($q) use ($filter_status) {
            $status = [
                'pending'=>null,
                'approved'=>1,
                'disapproved'=>2,
            ];
            $q->where('is_approved', $status[$filter_status]);
        })
        ->when($filter_month, function ($q) use ($filter_month) {
            $q->whereRaw('MONTH(overtime_date) = ?', [$filter_month]);
        })
        ->when($filter_year, function ($q) use ($filter_year) {
            $q->whereRaw('YEAR(overtime_date) = ?', [$filter_year]);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {

            // $last_updated_by = null;
            // if($item->updated_by != null){
            //     $last_updated_by = $item->updated_by_emp->fullname();
            // }elseif($item->created_by !=null){
            //     $last_updated_by = $item->created_by_emp->fullname();
            // }

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
                $approver_status = $approver->is_approved;
                $approver_type = $approver->approver_type == 1?'Department-Level':'Section-Level';
            }

            $item->count = $key + 1;
            $item->overtime_date = Carbon::parse($item->overtime_date)->format('m/d/Y');
            $item->overtime_from = Carbon::parse($item->overtime_from)->format('h:i A');
            $item->overtime_to = Carbon::parse($item->overtime_to)->format('h:i A');
            $item->approver_status = $approver_status;
            $item->approver_type = $approver_type;

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
            $isResubmit = false;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            $overtimeDate = Carbon::createFromFormat('m-d-Y', $rq->overtime_date)->format('Y-m-d');
            $overtimeFrom = Carbon::createFromFormat('H:i', $rq->overtime_from)->format('H:i');
            $overtimeTo = Carbon::createFromFormat('H:i', $rq->overtime_to)->format('H:i');

            $attribute = ['id'=>$id];
            $values = [
                'overtime_date' => $overtimeDate,
                'overtime_from' => $overtimeFrom,
                'overtime_to' => $overtimeTo,
                'reason' => $rq->reason,
            ];
            if($id==null){
                $values['emp_id']= $user_id;
                $values['created_by']= $user_id;
                $message = 'File successfully';
            }else{
                $values['updated_by']= $user_id;
                $message = 'Details is updated';
            }
            $query = HrisEmployeeOvertimeRequest::updateOrCreate($attribute,$values);

            //this will check if record is disapproved means its for resubmission
            if($query->is_approved == 2){
                $query->update([ 'is_approved'=> null ]);
                $isResubmit = true;
            }

            $isNotified = (new GroupApproverNotification)
            ->sendApprovalNotification($query,1,'approver.ot_request',$isResubmit);
            if($isNotified){
                DB::commit();
                return response()->json(['status' => 'success','message'=>$message]);
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

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeOvertimeRequest::find($id);

            $payload = [
                'overtime_date' =>Carbon::parse($query->overtime_date)->format('m-d-Y'),
                'overtime_from' =>Carbon::parse($query->overtime_from)->format('H:i'),
                'overtime_to' =>Carbon::parse($query->overtime_to)->format('H:i'),
                'reason' =>$query->reason,
                'is_approved'=>$query->is_approved,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function validate_request(Request $rq)
    {
        try{

            $validate_date = $this->check_date_request($rq);
            if(!$validate_date['valid']){
                return response()->json($validate_date);
            }

            $validate_date = $this->check_sick_leave($rq);
            if(!$validate_date['valid']){
                return response()->json($validate_date);
            }

            return ['valid' => true, 'message' => 'Eligible for filing overtime'];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function check_date_request(Request $rq)
    {
        $excluded_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id) : false;

        $overtimeFrom = Carbon::createFromFormat('H:i', $rq->overtime_from)->format('H:i');
        $overtimeTo = Carbon::createFromFormat('H:i', $rq->overtime_to)->format('H:i');
        $overtimeDate = Carbon::createFromFormat('m-d-Y', $rq->overtime_date)->format('Y-m-d');

        $exist = HrisEmployeeOvertimeRequest::where([
            ['emp_id', Auth::user()->emp_id],
            ['overtime_date',$overtimeDate],
            ['is_deleted', null],
        ])
        ->where(function ($query) use ($overtimeFrom, $overtimeTo) {
            $query->whereBetween('overtime_from', [$overtimeFrom, $overtimeTo])
                    ->orWhereBetween('overtime_to', [$overtimeFrom, $overtimeTo])
                    ->orWhere(function ($query) use ($overtimeFrom, $overtimeTo) {
                        $query->where('overtime_from', '<', $overtimeTo)
                            ->where('overtime_to', '>', $overtimeFrom);
                    });
        })
        ->when($excluded_id, function ($q) use ($excluded_id) {
            $q->where('id', '!=', $excluded_id);
        })
        ->exists();

        return ['valid' => !$exist, 'message' => 'The overtime date already exists or overlaps with an existing request.'];
    }

    public function check_sick_leave(Request $rq)
    {
        $overtimeDate = Carbon::createFromFormat('m-d-Y', $rq->overtime_date);
        $recentSickLeave = HrisEmployeeLeaveRequest::where([
            ['emp_id', Auth::user()->emp_id],
            ['leave_type_id', 2], // Sick leave
            ['is_deleted', null]
        ])
        ->where('leave_date_to', '<=', $overtimeDate)
        ->orderBy('leave_date_to', 'desc')
        ->first();
        if ($recentSickLeave) {
            $eligibleDateForOT = Carbon::parse($recentSickLeave->leave_end)->addDays(3);
            if ($overtimeDate->lt($eligibleDateForOT)) {
                return [
                    'valid' => false,
                    'message' => 'You can only file overtime requests starting from
                                 ' . $eligibleDateForOT->format('m/d/Y') . ' due to a recent sick leave.'
                ];
            }
        }
        return [ 'valid' => true, 'message' => 'Eligible for filing overtime' ];
    }
    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisEmployeeOvertimeRequest::find($id);
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Overtime request is deleted',
                'payload'=> HrisEmployeeOvertimeRequest::where([['emp_id',$user_id],['is_deleted',null]])->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function widgets()
    {
        try{
            $user_id = Auth::user()->emp_id;
            $results = HrisEmployeeOvertimeRequest::selectRaw("
                COUNT(*) as total_requests,
                SUM(CASE WHEN is_approved IS NULL THEN 1 ELSE 0 END) as pending_requests,
                SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved_requests,
                SUM(CASE WHEN is_approved = 2 THEN 1 ELSE 0 END) as rejected_requests
            ")
            ->where('emp_id', $user_id)
            ->where('is_deleted',null)
            ->first();

            return [
                'valid' => 'success',
                'message' => 'success',
                'payload' => [
                    'total_requests' => $results->total_requests ??0,
                    'pending_requests' => $results->pending_requests??0,
                    'approved_requests' => $results->approved_requests??0,
                    'rejected_requests' => $results->rejected_requests??0,
                ]
            ];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
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

            $overtimeRequest = HrisEmployeeOvertimeRequest::find($id);
            $overtimeRequestHistory = HrisApprovalHistory::with('employee')->where([['entity_id',$id],['entity_table',1]])->get();
            if(!$overtimeRequestHistory)
            {
                return response()->json(['status' => 'error','message'=>'Overtime Request Not Found']);
            }

            $history[] = [
                'is_approved' => 'pending',
                'action' => 'You filed a overtime request',
                'recorded_at'  => Carbon::parse($overtimeRequest->created_at)->format('M d, Y H:i A')
            ];

            if($overtimeRequestHistory->isNotEmpty())
            {
                foreach($overtimeRequestHistory as $data){
                    $action = '<span class="text-success fw-bold">Approved</span>'.' the overtime request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the overtime request';
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
