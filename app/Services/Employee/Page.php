<?php
namespace App\Services\Employee;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;

class Page
{

    public function employee_details($rq)
    {
        try{
            $query = Auth::user()->employee;
            $isRegisterEmployee = false;

            $emp_details = $query->emp_details;
            $tenure = Carbon::parse($emp_details->date_employed)->diffInYears(Carbon::now());
            $data = [
                'fullname'=>$query->fullname(),
                'department'=> $emp_details->department->name,
                'dept_code'=> $emp_details->department->code,
                'position'=> $emp_details->position->name,
                'date_employed'=> Carbon::parse($emp_details->date_employed)->format('m/d/Y'),
                'tenure'=> $tenure > 0 ? $tenure : '--',
                'employment_type' =>$emp_details->employment->name,
                'c_email'=> $query->emp_account->c_email,
                'work_status' => $emp_details->work_status,
            ];
            return view('employee.profile.profile', ['data'=>$data,'isRegisterEmployee'=>$isRegisterEmployee])->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

}
