<?php

namespace App\Http\Controllers\EmployeeController\Profile;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Services\Reusable\Select\ClassificationOptions;
use App\Services\Reusable\Select\CompanyLocationOptions;
use App\Services\Reusable\Select\CompanyOptions;
use App\Services\Reusable\Select\DepartmentOptions;
use App\Services\Reusable\Select\DocumentTypeOptions;
use App\Services\Reusable\Select\EmploymentTypeOptions;
use App\Services\Reusable\Select\PositionOptions;
use App\Services\Reusable\Select\SectionOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Tab extends Controller
{
    protected $isRegisterEmployee = false;

    public function form(Request $rq)
    {
        try {
            $emp_id = Auth::user()->emp_id;
            $components = 'components.employee-details.';
            $employee = Employee::with('emp_details')->find($emp_id);

            $view = match($rq->tab){
                '1',1=>self::personal_details($rq,$components,$employee),
                '2',2=>self::employment_details($rq,$components,$employee),
                '6',2=>self::document_attachments($rq,$components,$employee),
                '8',8=>self::account_security($rq,$components,$employee),
                default=>false,
            };

            if($view === false) { throw new \Exception("Form not found!"); }

            return response()->json([
                'status' =>'success',
                'message' => 'success',
                'payload' => base64_encode($view)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ],400);
        }
    }

    public function personal_details($rq,$components,$employee,)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        return view($components.'personal-information', compact('employee','isRegisterEmployee'))->render();
    }

    public function employment_details($rq, $components, $employee)
    {
        $emp_details = $employee->emp_details?$employee->emp_details:null;
        $isRegisterEmployee = $this->isRegisterEmployee;

        $classification_id  = $emp_details? Crypt::encrypt($emp_details->classification_id):null;
        $request = $rq->merge(['id' => $classification_id, 'type'=>'options']);
        $classification = (new ClassificationOptions)->list($request);

        $employment_id  = $emp_details? Crypt::encrypt($emp_details->employment_id):null;
        $request = $rq->merge(['id' => $employment_id, 'type'=>'options']);
        $employment_type = (new EmploymentTypeOptions)->list($request);

        $department_id  = $emp_details? Crypt::encrypt($emp_details->department_id):null;
        $request = $rq->merge(['id' => $department_id, 'type'=>'options']);
        $department = (new DepartmentOptions)->list($request);

        $section_id  = $emp_details? Crypt::encrypt($emp_details->section_id):null;
        $request = $rq->merge(['id' => $section_id, 'type'=>'options']);
        $section = (new SectionOptions)->list($request);

        $position_id  = $emp_details? Crypt::encrypt($emp_details->position_id):null;
        $request = $rq->merge(['id' => $position_id, 'type'=>'options']);
        $position = (new PositionOptions)->list($request);

        $company_id  = $emp_details? Crypt::encrypt($emp_details->company_id):null;
        $request = $rq->merge(['id' => $company_id, 'type'=>'options']);
        $company = (new CompanyOptions)->list($request);

        $company_location_id  = $emp_details? Crypt::encrypt($emp_details->company_location_id):null;
        $request = $rq->merge(['id' => $company_location_id, 'type'=>'options']);
        $company_location = (new CompanyLocationOptions)->list($request);

        $options = [
            'classification'=>$classification,
            'employment_type'=>$employment_type,
            'department'=>$department,
            'section'=>$section,
            'section'=>$section,
            'position'=>$position,
            'company'=>$company,
            'company_location'=>$company_location,
        ];

        return view($components.'employment-details', compact('employee','isRegisterEmployee','options'))->render();
    }

    public function document_attachments($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        $documents = $employee->documents;

        $request = $rq->merge(['id' => null, 'type'=>'options']);
        $document_type = (new DocumentTypeOptions)->list($request);

        $options = [
            'document_type'=>$document_type,
        ];
        return view($components.'document-attachments', compact('employee','isRegisterEmployee','options','documents'))->render();
    }

    public function account_security($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        $isSystemAdmin = false;
        $emp_account = $employee->emp_account;
        return view($components.'employee-account', compact('employee','isRegisterEmployee','emp_account','isSystemAdmin'))->render();
    }

}
