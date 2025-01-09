<?php

namespace App\Http\Controllers\AdminController\Settings\LeaveSettings;

use App\Http\Controllers\Controller;
use App\Models\HrisLeaveSetting;
use App\Models\HrisLeaveType;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class LeaveManagement extends Controller
{
    public function dt(Request $rq)
    {
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $data = HrisLeaveSetting::with('leave_type')->where('is_deleted',null)->orderBy('id', 'ASC') ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            $last_updated_at = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
                $last_updated_at = Carbon::parse($item->updated_at)->format('m-d-y h:iA');
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
                $last_updated_at = Carbon::parse($item->created_at)->format('m-d-y h:iA');
            }

            $leave_type = $item->leave_type;
            $item->count = $key + 1;

            $item->last_updated_by = $last_updated_by;
            $item->last_updated_at = $last_updated_at;
            $item->company_name = $leave_type->company->name;
            $item->leave_name = $leave_type->name;
            $item->leave_code = $leave_type->code;

            $item->credit_type_id = $item->credit_type;
            $item->credit_type = config('leave_values.credit_type.'.$item->credit_type);
            $item->fiscal_year = config('leave_values.fiscal_year.'.$item->fiscal_year);
            $item->assign_type = config('leave_values.assign_type.'.$item->assign_type);

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
            $query = HrisLeaveSetting::with('leave_type')->find($id);
            $payload = [
                'leave_type_id' =>$query->leave_type->name.' ('.$query->leave_type->code.')',
                'credit_type' =>$query->credit_type,
                'status' =>$query->status,
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
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $attribute = ['id'=>$id];
            $values = [
                'leave_type_id' =>Crypt::decrypt($rq->leave_type_id),
                'credit_type' =>$rq->credit_type,
                'fiscal_year' =>$rq->fiscal_year,
                'status' =>$rq->status,
            ];
            $query = HrisLeaveSetting::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([
                    'created_by'=>$user_id,
                ]);
                $message = 'Added successfully';
            }else{
                $query->update([
                    'updated_by' => $user_id,
                ]);
                $message = 'Details is updated';
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

            $check_existing_settings = $this->check_existing_settings($rq);
            if(!$check_existing_settings['valid']){
                return response()->json($check_existing_settings);
            }

            return ['valid' => true, 'message' => 'Valid'];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function check_existing_settings(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $exists = HrisLeaveSetting::where('leave_type_id', Crypt::decrypt($rq->leave_type_id))
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
            ->where('is_deleted',null)
            ->first();

            $valid = true;
            $message = 'Valid';
            if($exists){
                if($exists->status == 2){
                    $valid = false;
                    $message = 'Leave already exists, status is "Inactive"';
                }elseif($exists->status == 1){
                    $valid = false;
                    $message = 'Leave already exists, status is "Active"';
                }
            }
            return ['valid' => $valid ,'message' =>$message];
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisLeaveSetting::find($id);
            $query->status = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Record is removed',
                'payload' => HrisLeaveSetting::where('status',1)->count()
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
