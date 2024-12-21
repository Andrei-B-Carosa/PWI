<?php

namespace App\Http\Controllers\AdminController\Settings\GroupSettings;

use App\Http\Controllers\Controller;
use App\Models\HrisGroupApprover;
use App\Models\HrisGroupMember;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class GroupApprover extends Controller
{
    public function dt(Request $rq)
    {
        $group_id = Crypt::decrypt($rq->group_id);

        $data = HrisGroupApprover::with('employee')
        ->where([['is_deleted',null],['is_active','!=',0],['group_id',$group_id]])
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
            $item->position = $employee_details->position->name;

            $item->date_joined = Carbon::parse($item->created_at)->format('F j, Y');
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
            $query = HrisGroupApprover::with('employee')->find($id);
            $payload = [
                'approver_id' =>$query->employee->fullname(),
                'is_required' =>$query->is_required,
                'approver_level' =>$query->approver_level,
                'is_final_approver' =>$query->is_final_approver,
                'is_active' =>$query->is_active,
            ];
            return response()->json([
                'status' => 'success',
                'message'=>'success',
                'payload'=>base64_encode(json_encode($payload))
            ]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $group_id = Crypt::decrypt($rq->group_id);

            $attribute = ['group_id'=>$group_id];
            $values = [
                'is_active' =>$rq->is_active,
                'is_required' =>$rq->is_required,
                'approver_level' =>$rq->approver_level,
                'is_final_approver' =>$rq->is_final_approver,
            ];

            if(isset($rq->approver_id)){
                $attribute['emp_id'] = Crypt::decrypt($rq->approver_id);
                $values['created_by'] = $user_id;
                $message = 'Added successfully';
            }
            elseif(isset($rq->id)){
                $attribute['id'] = Crypt::decrypt($rq->id);
                $values['updated_by'] = $user_id;
                $message = 'Details is updated';
            }
            $query = HrisGroupApprover::updateOrCreate($attribute,$values);
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

            $query = HrisGroupApprover::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => HrisGroupApprover::where([['group_id',$query->group_id],['is_active',1]])->count()
            ]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function check_approver(Request $rq)
    {
        try {
            $approver_id = Crypt::decrypt($rq->approver_id);
            $group_id = Crypt::decrypt($rq->group_id);
            $excluded_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id) : false;

            $isExistingApprover = HrisGroupApprover::where([
                ['emp_id',$approver_id],
                ['group_id',$group_id],
                ['is_active',1],
            ])
            ->when($excluded_id,fn($q)=>
                $q->where('id', '!=', $excluded_id)
            )
            ->exists();

            if($isExistingApprover){
                $message = 'Approver already exist';
                return ['valid' => !$isExistingApprover, 'message'=>$message];
            }

            $isExistingMember = HrisGroupMember::where([
                ['emp_id',$approver_id],
                ['group_id',$group_id],
                ['is_active',1]
            ])->exists();

            if($isExistingMember){
                $message = "A group member can't be set as an approver";
                return ['valid' => !$isExistingMember, 'message'=>$message];
            }

            return ['valid' => 'true', 'message'=>'Valid'];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function check_approver_level(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $group_id = Crypt::decrypt($rq->group_id);
            $exists = HrisGroupApprover::where([
                ['approver_level',$rq->approver_level],
                ['group_id',$group_id],
                ['is_active',1],
            ])
            ->when($excluded_id,fn($q)=>
                $q->where('id', '!=', $excluded_id)
            )
            ->exists();
            if($exists){
                $message = 'Approver level already exist';
            }else{
                $message = 'Valid';
            }
            return ['valid' => !$exists, 'message'=>$message];
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function check_final_approver(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $group_id = Crypt::decrypt($rq->group_id);
            $exists = HrisGroupApprover::where([
                ['is_final_approver',$rq->is_final_approver],
                ['is_active',1],
                ['group_id',$group_id],
            ])
            ->when($excluded_id,fn($q)=>
                $q->where('id', '!=', $excluded_id)
            )
            ->exists();
            if($exists){
                $message = 'Final approver already exist';
            }else{
                $message = 'Valid';
            }
            return ['valid' => !$exists, 'message'=>$message];
        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

}
