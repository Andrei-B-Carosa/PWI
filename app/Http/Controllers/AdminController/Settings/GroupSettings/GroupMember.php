<?php

namespace App\Http\Controllers\AdminController\Settings\GroupSettings;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisGroupMember;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class GroupMember extends Controller
{
    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $group_id = Crypt::decrypt($rq->group_id);
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = HrisGroupMember::with('employee')
        ->where([['is_deleted',null],['is_active',1],['group_id',$group_id]])
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;

            $employee_details = $item->employee->emp_details;

            $item->emp_name = $item->employee->fullname();
            $item->emp_no = $item->employee->emp_no;
            $item->emp_department = $employee_details->department->name;
            $item->emp_position = $employee_details->position->name;

            $item->date_joined = Carbon::parse($item->created_at)->format('F j, Y');

            $item->position = $employee_details->position->name;
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
            $emp_id = Crypt::decrypt($rq->id);
            $group_id = Crypt::decrypt($rq->group_id);

            $attribute = [
                'emp_id'=>$emp_id,
                'group_id'=>$group_id,
            ];
            $values = ['is_active' =>$rq->is_active];

            $query = HrisGroupMember::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id ]);
                $message = 'Added successfully';
            }else{
                $query->update([ 'updated_by' => $user_id ]);
                $message = 'Details is updated';
            }
            DB::commit();
            return response()->json(['status' => 'success', 'message'=>$message]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = HrisGroupMember::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => HrisGroupMember::where([['group_id',$query->group_id],['is_active',1]])->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function employee_list(Request $rq)
    {

        $group_id = Crypt::decrypt($rq->group_id);
        $data = Employee::where([['is_deleted',null],['is_active',1]])
        ->whereDoesntHave('group_member', function ($q) {
            $q->where('is_active', 1);
        })
        ->whereDoesntHave('group_approver', function ($q) use ($group_id) {
            $q->where('group_id', $group_id)
              ->where('is_active', 1);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;

            $employee_details = $item->emp_details;

            $item->emp_name = $item->fullname();
            $item->emp_no = $item->emp_no;
            $item->emp_department = $employee_details->department->name;
            $item->emp_position = $employee_details->position->name;

            $item->position = $employee_details->position->name;
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
}
