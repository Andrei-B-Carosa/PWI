<?php

namespace App\Http\Controllers\AdminController\Employee\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisEmployeeEducation;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EducationalBackground extends Controller
{
    protected $isRegisterEmployee = false;

    public function dt(Request $rq)
    {
        $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;

        $data = HrisEmployeeEducation::where([['is_deleted',null],['emp_id',$emp_id]])->orderBy('id', 'ASC') ->get();
        $data->transform(function ($item, $key) {

            $last_updated_by = null;
            if($item->updated_by != null){
                $last_updated_by = $item->updated_by_emp->fullname();
            }elseif($item->created_by !=null){
                $last_updated_by = $item->created_by_emp->fullname();
            }

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->date_from = Carbon::parse($item->date_from)->format('m/d/Y');
            $item->date_to = Carbon::parse($item->date_to)->format('m/d/Y');
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

    public function view($rq,$components,$employee)
    {
        $isRegisterEmployee = $this->isRegisterEmployee;
        return view($components.'educational-background', compact('employee','isRegisterEmployee'))->render();
    }

    public function info(Request $rq)
    {
        try {
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $query = HrisEmployeeEducation::find($id);
            $payload = [
                'school'=>$query->school,
                'level'=>$query->level,
                'degree'=>$query->degree,
                'honors'=>$query->honors,
                'units'=>$query->units,
                'date_from' =>Carbon::parse($query->date_from)->format('m-d-Y'),
                'date_to' =>Carbon::parse($query->date_to)->format('m-d-Y'),
                'year_graduate'=>$query->year_graduate,
            ];
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);

        } catch (\Exception $e) {
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();
            $user_id = Auth::user()->id;
            $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;

            $attribute = [
                'id' =>$id,
                'emp_id' =>$emp_id
            ];
            $value = [
                'school'=>$rq->school,
                'level'=>$rq->level,
                'degree'=>$rq->degree,
                'honors'=>$rq->honors,
                'units'=>$rq->units,
                'date_from'=>Carbon::createFromFormat('m-d-Y',$rq->date_from)->format('Y-m-d'),
                'date_to'=>Carbon::createFromFormat('m-d-Y',$rq->date_to)->format('Y-m-d'),
                'year_graduate'=>$rq->year_graduate,
                isset($id)?'updated_by':'created_by' =>$user_id,
            ];

            if(isset($rq->supporting_document)){
                $emp_no = preg_replace('/[^A-Za-z0-9]/','',Employee::find($emp_id)->emp_no);
                $document = preg_replace('/\s+/', '',config('document_values.school_document.'.$rq->level));

                $filename = $emp_no.'_'.$document.'.'.$rq->file('supporting_document')->getClientOriginalExtension();
                $filePath = $rq->file('supporting_document')->storeAs('employee/'.$emp_no.'/documents', $filename,'public');

                if (Storage::disk('public')->exists($filePath)) {$value['supporting_document'] = $filename; }
            }

            HrisEmployeeEducation::updateOrCreate($attribute,$value);
            DB::commit();
            return [ 'status' => 'success','message'=>'Education is updated', 'payload'=>'' ];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }

    }
}
