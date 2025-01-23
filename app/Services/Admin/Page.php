<?php
namespace App\Services\Admin;

use App\Models\Employee;
use App\Models\HrisGroup;
use App\Models\HrisLeaveSetting;
use App\Services\Reusable\Select\ClassificationOptions;
use App\Services\Reusable\Select\CompanyLocationOptions;
use App\Services\Reusable\Select\EmploymentTypeOptions;
use App\Services\Reusable\Select\FilterYearOptions;
use App\Services\Reusable\Select\LeaveFiscalYearOptions;
use App\Services\Reusable\Select\MonthsDayOptions;
use Exception;
use Illuminate\Support\Facades\Crypt;

class Page
{
    public function automatic_credit($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisLeaveSetting::with('leave_type')->where('id',$id)->first();

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $classification_options = (new ClassificationOptions)->list($request);

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $employment_type_options = (new EmploymentTypeOptions)->list($request);

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $locations_options = (new CompanyLocationOptions)->list($request);

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $effectivity_options = (new LeaveFiscalYearOptions)->list($request);

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            [$month_options, $day_options] = (new MonthsDayOptions)->list($request);

            $data = [
                'leave_type' => $query->leave_type->name .' ('.$query->leave_type->code.')',
                'classification'=>$classification_options,
                'employment' =>$employment_type_options,
                'location' =>$locations_options,
                'effectivity' =>$effectivity_options,
                'month' =>$month_options,
                'day' =>$day_options,
            ];
            return view('admin.settings.leave_settings.automatic_credit', compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function manual_credit($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = HrisLeaveSetting::with('leave_type')->where('id',$id)->first();

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $year_options = (new FilterYearOptions)->get_leave_year();

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $classification_options = (new ClassificationOptions)->list($request);

            $request = $rq->merge(['id' => null, 'type'=>'options']);
            $employment_type_options = (new EmploymentTypeOptions)->list($request);

            $data = [
                'leave_type' => $query->leave_type->name .' ('.$query->leave_type->code.')',
                'gender_type' =>$query->leave_type->gender_type,
                'year'=>$year_options,
                'classification'=>$classification_options,
                'employment' =>$employment_type_options,
            ];
            return view('admin.settings.leave_settings.manual_credit', compact('data'))->render();

        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function employee_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = Employee::find($id);
            $isRegisterEmployee = false;
            return view('admin.201_employee.employee_details.employee_details', ['data'=>$data,'isRegisterEmployee'=>$isRegisterEmployee])->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }

    public function group_details($rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $data = HrisGroup::find($id);
            return view('admin.settings.group_settings.group_details',compact('data'))->render();
        } catch(Exception $e) {
            return response()->json([
                'status' => 400,
                // 'message' =>  'Something went wrong. try again later'
                'message' =>  $e->getMessage()
            ]);
        }
    }
}
