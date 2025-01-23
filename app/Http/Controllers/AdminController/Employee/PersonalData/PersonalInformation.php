<?php

namespace App\Http\Controllers\AdminController\Employee\PersonalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PersonalInformation extends Controller
{
    public function personal_information($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        return view($components.'personal-information', compact('employee','isRegisterEmployee'))->render();
    }

    public function update_personal_info($rq)
    {
        try {
            DB::beginTransaction();
            $emp_id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
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
}
