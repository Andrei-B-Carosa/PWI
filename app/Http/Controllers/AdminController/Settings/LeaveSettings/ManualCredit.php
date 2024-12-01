<?php

namespace App\Http\Controllers\AdminController\Settings\LeaveSettings;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisEmployeeLeaveBalance;
use App\Models\HrisLeaveSetting;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ManualCredit extends Controller
{
    public function dt(Request $rq)
    {
        // $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_classification = $rq->filter_classification != 'all' ? Crypt::decrypt($rq->filter_classification) : false;
        $filter_employment = $rq->filter_employment != 'all' ? Crypt::decrypt($rq->filter_employment) : false;

        $leave_setting_id = Crypt::decrypt($rq->leave_setting_id);
        $leave_setting = HrisLeaveSetting::with('leave_type')->find($leave_setting_id);
        $gender_type = $leave_setting->leave_type->gender_type;

        $data = Employee::with([
            'emp_leave_balance' => fn($q) => $q->where('leave_type_id', $leave_setting->leave_type_id),
            'emp_position'])
        ->when($filter_classification, fn($q) =>
            $q->whereHas('emp_position', fn($query) =>
                $query->where('classification_id', $filter_classification)
            )
        )
        ->when($filter_employment, fn($q) =>
            $q->whereHas('emp_position', fn($query) =>
                $query->where('employment_id', $filter_employment)
            )
        )
        ->when($gender_type != 3, fn($q) =>
            $q->where('sex', $gender_type)
        )
        ->where([['is_active',1],['is_deleted',null]])
        ->get();

        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $leave_balance = 0;
            $url = 'create';
            if($item->emp_leave_balance->isNotEmpty()){
                $leave_balance = $item->emp_leave_balance[0]->leave_balance;
                $url = 'update';
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;

            $item->employee_name = $item->fullname();
            $item->position = $item->emp_position->position->name;
            $item->classification = $item->emp_position->classification->name;
            $item->department = $item->emp_position->department->name;
            $item->employment = $item->emp_position->employment->name;

            $item->manual_leave_credt = 0;
            $item->available_leave = $leave_balance;
            $item->url = $url;


            $item->sex = $item->sex==1?'Male':'Female';

            $item->date_employed = Carbon::parse($item->date_employed)->format('m-d-Y');


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

    public function create(Request $rq)
    {
        try{
            DB::beginTransaction();

            $user_id = Auth::user()->emp_id;

            $leave_setting_id = Crypt::decrypt($rq->leave_setting_id);
            $leave_setting = HrisLeaveSetting::with('leave_type')->find($leave_setting_id);
            $emp_id = Crypt::decrypt($rq->emp_id);

            HrisEmployeeLeaveBalance::create([
                'leave_type_id'=>$leave_setting->leave_type_id,
                'emp_id' =>$emp_id,
                'leave_balance' =>$rq->leave_balance,
                'is_active' =>1,
                'created_by'=>$user_id
            ]);

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Leave credit is added successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;

            $leave_setting_id = Crypt::decrypt($rq->leave_setting_id);
            $leave_setting = HrisLeaveSetting::with('leave_type')->find($leave_setting_id);
            $emp_id = Crypt::decrypt($rq->emp_id);

            $emp_leave_balance = HrisEmployeeLeaveBalance::where([
                ['emp_id',$emp_id],
                ['leave_type_id',$leave_setting->leave_type_id],
                ['is_active',1]
            ])->first();

            $update_balance = $emp_leave_balance->leave_balance + $rq->leave_balance;

            $emp_leave_balance->leave_balance = $update_balance;
            $emp_leave_balance->updated_by = $user_id;
            $emp_leave_balance->save();

            DB::commit();
            return response()->json(['status' => 'success','message'=>'Leave credit is updated successfully']);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
