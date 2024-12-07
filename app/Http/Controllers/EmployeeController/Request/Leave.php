<?php

namespace App\Http\Controllers\EmployeeController\Request;

use App\Http\Controllers\Controller;
use App\Models\HrisEmployeeLeaveBalance;
use App\Models\HrisEmployeeLeaveRequest;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Leave extends Controller
{
    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;

        $data = HrisEmployeeLeaveRequest::with('latest_approval_histories')
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
            $q->whereRaw('MONTH(leave_filing_date) = ?', [$filter_month]);
        })
        ->when($filter_year, function ($q) use ($filter_year) {
            $q->whereRaw('YEAR(leave_filing_date) = ?', [$filter_year]);
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
            if($item->latest_approval_histories){
                $approved_by = $approver->employee->fullname();
                $approver_level = 'Level '.$approver->approver_level;
                $approver_remarks = $approver->approver_remarks;
                $approver_status = $approver->is_approved;
                $approver_type = $approver->approver_type == 1?'Department-Level':'Section-Level';
            }

            $item->count = $key + 1;
            $item->leave_filing_date = Carbon::parse($item->leave_filing_date)->format('m/d/Y');
            $item->leave_date_from = Carbon::parse($item->leave_date_from)->format('m/d/Y');
            $item->leave_date_to = Carbon::parse($item->leave_date_to)->format('m/d/Y');
            $item->approver_status = $approver_status;
            $item->approver_type = $approver_type;

            $item->leave_type = $item->leave_type->name.' ('.$item->leave_type->code.')';
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
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $leaveDate = Carbon::createFromFormat('m-d-Y', $rq->leave_filing_date)->format('Y-m-d');
            $leaveFrom = Carbon::createFromFormat('m-d-Y', $rq->leave_date_from)->format('Y-m-d');
            $leaveTo = Carbon::createFromFormat('m-d-Y', $rq->leave_date_to)->format('Y-m-d');

            $attribute = ['id'=>$id];
            $values = [
                'leave_filing_date' => $leaveDate,
                'leave_date_from' => $leaveFrom,
                'leave_date_to' => $leaveTo,
                'reason' => $rq->reason,
                'is_excused' => $rq->is_excused,
            ];

            if($id == null){
                $values['emp_id'] = $user_id;
                $values['leave_type_id'] = Crypt::decrypt($rq->leave_type_id);
                $message = 'File successfully';
            }else{
                $vales['updated_by']= $user_id;
                $message = 'Details is updated';
            }

            $query = HrisEmployeeLeaveRequest::updateOrCreate($attribute,$values);
            if($query->is_approved == 2){
                $query->update([ 'is_approved'=> null ]);
            }

            DB::commit();
            return response()->json(['status' => 'success','message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function validate_request(Request $rq)
    {
        try{

            $check_date_request = $this->check_date_request($rq);
            if(!$check_date_request['valid']){
                return response()->json($check_date_request);
            }

            return ['valid' => true, 'message' => 'Eligible for filing leave'];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function check_date_request(Request $rq)
    {
        $excluded_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id) : false;

        $leaveFrom = Carbon::createFromFormat('m-d-Y', $rq->leave_date_from)->format('Y-m-d');
        $leaveTo = Carbon::createFromFormat('m-d-Y', $rq->leave_date_to)->format('Y-m-d');

        $exist = HrisEmployeeLeaveRequest::where([
            ['emp_id', Auth::user()->emp_id],
            ['is_deleted', null],
            // ['is_approved','!=',2]
        ])
        ->where(function ($query) use ($leaveFrom, $leaveTo) {
            $query->whereBetween('leave_date_from', [$leaveFrom, $leaveTo])
                    ->orWhereBetween('leave_date_to', [$leaveFrom, $leaveTo])
                    ->orWhere(function ($query) use ($leaveFrom, $leaveTo) {
                        $query->where('leave_date_from', '<', $leaveTo)
                            ->where('leave_date_to', '>', $leaveFrom);
                    });
        })
        ->when($excluded_id, function ($q) use ($excluded_id) {
            $q->where('id', '!=', $excluded_id);
        })
        ->exists();

        return [
            'valid' => !$exist,
            'message' => 'The date(s) of leave already exists or overlaps with an existing request.'
        ];
    }

    public function check_leave_balance(Request $rq)
    {
        $user_id = Auth::user()->emp_id;
        $leave_type_id = Crypt::decrypt($rq->leave_type_id);
        $excluded_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id) : false;

        if($excluded_id){
            return ['valid' => true, 'message' => 'Eligible for updating'];
        }

        $leave_balance = HrisEmployeeLeaveBalance::where([['emp_id',$user_id],['leave_type_id',$leave_type_id]])->value('leave_balance');
        if (!$leave_balance || $leave_balance <= 0) {
            return ['valid' => false, 'message' => 'Insufficient leave balance'];
        }

        $leaveFrom = Carbon::createFromFormat('m-d-Y', $rq->leave_date_from);
        $leaveTo = Carbon::createFromFormat('m-d-Y', $rq->leave_date_to);

        $daysCount = 0;
        for ($date = $leaveFrom->copy(); $date->lte($leaveTo); $date->addDay()) {
            if ($date->isWeekend()) {
                continue;
            }
            $daysCount++;
        }

        $pendingDays = HrisEmployeeLeaveRequest::where('emp_id', $user_id)
        ->where('leave_type_id', $leave_type_id)
        ->where('is_approved', null) // Pending leave requests
        ->where(function ($query) use ($leaveFrom, $leaveTo) {
            $query->whereBetween('leave_date_from', [$leaveFrom, $leaveTo])
                ->orWhereBetween('leave_date_to', [$leaveFrom, $leaveTo])
                ->orWhereRaw('? BETWEEN leave_date_from AND leave_date_to', [$leaveFrom])
                ->orWhereRaw('? BETWEEN leave_date_from AND leave_date_to', [$leaveTo]);
        })
        ->get()
        ->reduce(function ($total, $leave) {
            $from = max($leave->leave_date_from, request()->leave_date_from);
            $to = min($leave->leave_date_to, request()->leave_date_to);
            $period = CarbonPeriod::create($from, $to);
            foreach ($period as $date) {
                if (!$date->isWeekend()) {
                    $total++;
                }
            }
            return $total;
        }, 0);

        // Adjust balance for pending leave days
        $effectiveBalance = $leave_balance - $pendingDays;

        if ($daysCount > $effectiveBalance) {
            return ['valid' => false, 'message' => 'Insufficient leave balance '.($pendingDays>0?'(including pending requests if any)':'').''];
        }

        return ['valid' => true, 'message' => 'Eligible for filing leave'];
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeLeaveRequest::find($id);
            $leave_type = $query->leave_type;

            $payload = [
                'leave_filing_date' =>Carbon::parse($query->leave_filing_date)->format('m-d-Y'),
                'leave_date_from' =>Carbon::parse($query->leave_date_from)->format('m-d-Y'),
                'leave_date_to' =>Carbon::parse($query->leave_date_to)->format('m-d-Y'),
                'reason' =>$query->reason,
                'is_excused' =>$query->is_excused,
                'leave_type' => $leave_type->name.' ('.$leave_type->code.')',
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    // public function validate_request(Request $rq)
    // {
    //     try{

    //         $validate_date = $this->check_date_request($rq);
    //         if(!$validate_date['valid']){
    //             return response()->json($validate_date);
    //         }

    //         $validate_date = $this->check_leave_request($rq);
    //         if(!$validate_date['valid']){
    //             return response()->json($validate_date);
    //         }

    //         return ['valid' => true, 'message' => 'Eligible for filing overtime'];

    //     }catch(Exception $e)
    //     {
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);
    //     }
    // }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisEmployeeLeaveRequest::find($id);
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Leave request is deleted',
                'payload'=> HrisEmployeeLeaveBalance::where([['emp_id',$user_id],['is_deleted',null]])->count()
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
