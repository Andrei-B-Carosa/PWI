<?php

namespace App\Http\Controllers\AdminController\Settings;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovingOfficer;
use App\Models\HrisGroup;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ApproverSettings extends Controller
{
    // public function dt(Request $rq)
    // {
    //     // $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

    //     $data = HrisApprovingOfficer::with(['employee','position','department','company','company_location'])
    //     ->where([['is_active',1],['is_deleted',null]])->get();

    //     $data->transform(function ($item, $key) {

    //         $last_updated_by = null;
    //         $last_updated_date = null;
    //         if($item->updated_by != null){
    //             $last_updated_by = optional($item->updated_by_emp)->fullname();
    //             $last_updated_date = Carbon::parse($item->updated_at)->format('m-d-Y');
    //         }elseif($item->created_by !=null){
    //             $last_updated_by = optional($item->created_by_emp)->fullname();
    //             $last_updated_date = Carbon::parse($item->created_at)->format('m-d-Y');
    //         }

    //         $item->count = $key + 1;
    //         $item->last_updated_by = $last_updated_by;
    //         $item->last_updated_date = $last_updated_date;

    //         $item->employee_name = optional($item->employee)->fullname() ?? '';
    //         $item->emp_no = optional($item->employee)->emp_no ?? '';
    //         $item->department_name = $item->department->name.' ('.$item->department->code.')';
    //         $item->section_name = $item->section->name;

    //         $item->encrypted_id = Crypt::encrypt($item->id);
    //         return $item;
    //     });

    //     $table = new DTServerSide($rq, $data);
    //     $table->renderTable();

    //     return response()->json([
    //         'draw' => $table->getDraw(),
    //         'recordsTotal' => $table->getRecordsTotal(),
    //         'recordsFiltered' =>  $table->getRecordsFiltered(),
    //         'data' => $table->getRows()
    //     ]);
    // }

    // public function check_approver(Request $rq)
    // {
    //     try {
    //         $approver_id = Crypt::decrypt($rq->approver_id);
    //         $department_id = isset($rq->department_id) ? Crypt::decrypt($rq->department_id) : false;
    //         $excluded_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id) : false;
    //         $section_id = isset($rq->section_id) ? Crypt::decrypt($rq->section_id) : null;

    //         $check_department_approver = $this->check_department_approver($approver_id,$department_id,$excluded_id,$section_id);
    //         if(!$check_department_approver['valid']){
    //             return response()->json($check_department_approver);
    //         }

    //         // $check_section_approver = $this->check_section_approver($approver_id,$department_id,$excluded_id,$section_id);
    //         // if(!$check_section_approver['valid']){
    //         //     return response()->json($check_section_approver);
    //         // }

    //         return ['valid' => true , 'message'=>'Valid'];
    //     } catch (\Exception $e) {
    //         return [
    //             'valid' => false,
    //             'message' => $e->getMessage(),
    //         ];
    //     }
    // }

    // public function check_department_approver($approver_id,$department_id,$excluded_id,$section_id){
    //     $exists = HrisApprovingOfficer::where('emp_id', $approver_id)
    //     ->where('department_id', $department_id)
    //     ->where('is_active', 1)
    //     ->where('section_id', null)
    //     ->when($excluded_id, fn($q) => $q->where('id', '!=', $excluded_id))
    //     ->exists();
    //     if($exists){
    //         return ['valid' => false , 'message'=>'Employee is already approver on selected department'];
    //     }
    //     return ['valid' => true , 'message'=>'Valid'];
    // }

    // public function check_section_approver($approver_id,$department_id,$excluded_id,$section_id)
    // {
    //     $exists = HrisApprovingOfficer::where('emp_id', $approver_id)
    //     ->where('department_id', $department_id)
    //     ->where('is_active', 1)
    //     ->when($section_id !=null, fn($q)=> $q->where('section_id', $section_id))
    //     ->when($section_id ==null, fn($q)=> $q->whereNotNull('section_id'))
    //     ->when($excluded_id, fn($q) => $q->where('id', '!=', $excluded_id))
    //     ->exists();
    //     if($exists){
    //         return ['valid' => false , 'message'=>'Approver already exist as section-level approver'];
    //     }
    //     return ['valid' => true , 'message'=>'Valid'];
    // }

    // public function check_approver_level(Request $rq)
    // {
    //     try{
    //         $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
    //         $section_id = isset($rq->section_id)?Crypt::decrypt($rq->section_id):null;
    //         $department_id = isset($rq->department_id)?Crypt::decrypt($rq->department_id):null;
    //         $approver_level = $rq->approver_level;

    //         $exists = HrisApprovingOfficer::where([
    //             ['department_id',$department_id],
    //             ['approver_level',$approver_level],
    //             ['is_active',1],
    //             ['section_id',$section_id],
    //         ])
    //         ->when($excluded_id,fn($q)=>
    //             $q->where('id', '!=', $excluded_id)
    //         )
    //         ->exists();

    //         if($exists){
    //             $message = 'Approver level already exist';
    //         }else{
    //             $message = 'Valid';
    //         }

    //         return ['valid' => !$exists, 'message'=>$message];
    //     }catch(Exception $e)
    //     {
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);
    //     }
    // }

    // public function check_final_approver(Request $rq)
    // {
    //     try{
    //         $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
    //         $section_id = isset($rq->section_id)?Crypt::decrypt($rq->section_id):null;
    //         $department_id = Crypt::decrypt($rq->department_id);

    //         $exists = HrisApprovingOfficer::where([
    //             ['department_id',$department_id],
    //             ['is_final_approver',$rq->is_final_approver],
    //             ['is_active',1],
    //             ['section_id',$section_id],
    //         ])
    //         ->when($excluded_id,fn($q)=>
    //             $q->where('id', '!=', $excluded_id)
    //         )
    //         ->exists();

    //         if($exists){
    //             $message = 'Final approver already exist';
    //         }else{
    //             $message = 'Valid';
    //         }

    //         return ['valid' => !$exists, 'message'=>$message];
    //     }catch(Exception $e)
    //     {
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);
    //     }
    // }

    // public function info(Request $rq)
    // {
    //     try{
    //         $id = Crypt::decrypt($rq->id);
    //         $query = HrisApprovingOfficer::with([
    //             'employee',
    //             'company',
    //             'company_location',
    //             'department',
    //             'section',
    //             ])->find($id);
    //         $payload = [
    //             'approver_id' =>$query->employee->fullname(),
    //             'approver_level' =>$query->approver_level,
    //             'company_id' =>$query->company->name,
    //             'company_location_id' =>$query->company_location->name,
    //             'department_id' =>$query->department->name,
    //             'section_id' =>$query->section->name,
    //             'is_required' =>$query->is_required,
    //             'is_final_approver' =>$query->is_final_approver,
    //             'is_active' =>$query->is_active,
    //         ];
    //         return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
    //     }catch(Exception $e){
    //         return response()->json(['status'=>400,'message' =>$e->getMessage()]);
    //     }
    // }

    // public function create(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();

    //         $user_id = Auth::user()->emp_id;
    //         HrisApprovingOfficer::create([
    //             'emp_id' =>Crypt::decrypt($rq->approver_id),
    //             'approver_level' =>$rq->approver_level,
    //             'company_id' =>Crypt::decrypt($rq->company_id),
    //             'company_location_id' =>Crypt::decrypt($rq->company_location_id),
    //             'department_id' =>Crypt::decrypt($rq->department_id),
    //             'section_id' =>$rq->section_id?Crypt::decrypt($rq->section_id):null,
    //             'is_required' =>$rq->is_required,
    //             'is_final_approver' =>$rq->is_final_approver,
    //             'is_active' =>$rq->is_active,
    //             'created_by'=>$user_id,
    //         ]);

    //         DB::commit();
    //         return response()->json(['status' => 'success','message'=>'Created Successfully']);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    // public function update(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $user_id = Auth::user()->emp_id;
    //         $id =  Crypt::decrypt($rq->id);

    //         $query = HrisApprovingOfficer::find($id);
    //         $query->approver_level =$rq->approver_level;
    //         $query->is_required =$rq->is_required;
    //         $query->is_final_approver =$rq->is_final_approver;
    //         $query->is_active =$rq->is_active;
    //         $query->updated_by = $user_id;
    //         $query->save();

    //         DB::commit();
    //         return response()->json(['status' => 'success','message'=>'Details is updated']);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    // public function delete(Request $rq)
    // {
    //     try{
    //         DB::beginTransaction();
    //         $user_id = Auth::user()->emp_id;
    //         $id =  Crypt::decrypt($rq->id);

    //         $query = HrisApprovingOfficer::find($id);
    //         $query->is_active = 0;
    //         $query->is_deleted = 1;
    //         $query->deleted_by = $user_id;
    //         $query->deleted_at = Carbon::now();
    //         $query->save();

    //         DB::commit();
    //         return response()->json([
    //             'status' => 'info',
    //             'message'=>'Record is removed',
    //             'payload' => HrisApprovingOfficer::where('is_active',1)->count()
    //         ]);
    //     }catch(Exception $e){
    //         DB::rollback();
    //         return response()->json([
    //             'status' => 400,
    //             'message' => $e->getMessage(),
    //         ]);
    //     }
    // }

    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;

        $data = HrisGroup::withCount(['group_members', 'group_approvers'])
        ->where([
            ['is_deleted',null],['is_active','!=',0]
        ])
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {
            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->members_count = $item->group_members_count;
            $item->approvers_count = $item->group_approvers_count;

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
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
            $query = HrisGroup::find($id);
            $payload = [
                'name' =>$query->name,
                'description' =>$query->description,
                'is_active' =>$query->is_active,
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
                'name' =>ucwords(strtolower($rq->name)),
                'description' =>$rq->description,
                'is_active' =>$rq->is_active,
            ];
            $query = HrisGroup::updateOrCreate($attribute,$values);
            if ($query->wasRecentlyCreated) {
                $query->update([ 'created_by'=>$user_id, ]);
                $message = 'Added successfully';
            }else{
                $query->update([ 'updated_by' => $user_id, ]);
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

    public function validate_request(Request $rq)
    {
        try{
            $excluded_id = isset($rq->id) && $rq->id != "undefined"? Crypt::decrypt($rq->id): false;
            $exists = HrisGroup::where('name', ucwords(strtolower($rq->name)))
            ->when($excluded_id, function ($q) use ($excluded_id) {
                $q->where('id', '!=', $excluded_id);
            })
            ->where('is_active','!=',0)
            ->exists();
            return response()->json(['valid' => !$exists, 'message' => 'This classification is already in use']);
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

            $query = HrisGroup::find($id);
            $query->is_active = 0;
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'Removed successfully',
                'payload' => HrisGroup::where('is_active',1)->count()
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
