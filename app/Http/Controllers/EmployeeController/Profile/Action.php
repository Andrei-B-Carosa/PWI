<?php

namespace App\Http\Controllers\EmployeeController\Profile;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeAccount;
use App\Models\HrisEmployeePosition;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Action extends Controller
{
    public function update(Request $rq)
    {
        try {
            $res = match ($rq->tab) {
                '1',1=> self::update_personal_info($rq),
                '2',2=> self::update_employment_info($rq),
                '8',8=> self::update_account_security($rq),
                default => throw new \Exception("Form not found!"),
            };

            if(!$res['status']){  return response()->json($res);  }

            return response()->json([
                'status' =>$res['status'],
                'message' =>$res['message'] ,
                'payload' => $res['payload'],
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function update_personal_info($rq)
    {
        try {
            DB::beginTransaction();
            $emp_id = Auth::user()->emp_id;
            $user_id = Auth::user()->id;

            $attribute = ['id' =>$emp_id];
            $value = [
                'fname'=>$rq->fname,
                'lname'=>$rq->lname,
                'mname'=>$rq->mname,
                'ext'=>$rq->ext,
                'birthday'=>Carbon::createFromFormat('m-d-Y',$rq->birthdate)->format('Y-m-d'),
                'birthplace'=>$rq->birthplace,
                'sex'=>$rq->sex,
                'civil_status'=>$rq->civil_status,
                'telephone_number'=>$rq->telephone,
                'mobile_number'=>$rq->mobile,
                'p_email'=>$rq->email,
                'citizenship'=>$rq->citizenship,
                'height'=>$rq->height,
                'weight'=>$rq->weight,
                'blood_type'=>$rq->blood_type,
                'gsis'=>$rq->gsis,
                'pagibig'=>$rq->pagibig,
                'philhealth'=>$rq->philhealth,
                'sss'=>$rq->sss,
                'tin'=>$rq->tin,
                'is_active'=>1,
                'updated_by' => $user_id
                // 'agency'=>$rq->agency
            ];

            Employee::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload'=>'' ];
        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function update_employment_info($rq)
    {
        try {
            DB::beginTransaction();
            $emp_id = Auth::user()->emp_id;

            $company_id = Crypt::decrypt($rq->company_id);
            $location_id = Crypt::decrypt($rq->location_id);
            $department_id = Crypt::decrypt($rq->department_id);
            $section_id = Crypt::decrypt($rq->section_id);
            $employment_id = Crypt::decrypt($rq->employment_id);
            $position_id = Crypt::decrypt($rq->position_id);
            $classification_id = Crypt::decrypt($rq->classification_id);

            $query = Employee::find($emp_id);
            if(!$query){
                throw new \Exception("Employee record not found!");
            }

            $attribute = ['emp_id' =>$emp_id];
            $value = [
                'company_id'=>$company_id,
                'company_location_id'=>$location_id,
                'department_id'=>$department_id,
                'section_id'=>$section_id,
                'employment_id'=>$employment_id,
                'position_id'=>$position_id,
                'classification_id'=>$classification_id,
                'is_active'=>$rq->is_active,
                'date_employed'=>Carbon::createFromFormat('m-d-Y',$rq->date_employed)->format('Y-m-d'),
                'work_status'=>$rq->work_status,
                'updated_by' => $emp_id,
            ];
            HrisEmployeePosition::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function update_account_security($rq)
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::user()->emp_id;
            $query = EmployeeAccount::where([['emp_id',$user_id],['is_active',1]])->first();

            if($rq->column == 'password'){
                if($rq->newpassword !== $rq->confirmpassword){
                    return [ 'status' => 'success','message'=>'Passwords do not match', 'payload' => ''];
                }
                if (!Hash::check($rq->currentpassword, $query->password)) {
                    return [ 'status' => 'error','message'=>'Current password do not match', 'payload' => ''];
                }
                $values = Hash::make($rq->newpassword);
            }elseif ($rq->column == 'c_email') {
                $values = $rq->emailaddress;
            } else {
                return [ 'status' => 'error', 'message' => 'Invalid column' ];
            }
            // andrei@2024A
            $query->{$rq->column} = $values;
            $query->updated_by = $user_id;
            $query->save();

            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }
}
