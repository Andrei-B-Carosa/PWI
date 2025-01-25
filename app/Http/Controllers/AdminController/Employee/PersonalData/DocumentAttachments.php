<?php

namespace App\Http\Controllers\AdminController\Employee\PersonalData;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\HrisEmployeeDocument;
use App\Services\Reusable\DTServerSide;
use App\Services\Reusable\Select\DocumentTypeOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DocumentAttachments extends Controller
{

    protected $isRegisterEmployee = false;

    public function dt(Request $rq)
    {
        $emp_id = isset($rq->emp_id) && $rq->emp_id != "undefined" ? Crypt::decrypt($rq->emp_id):null;

        $data = HrisEmployeeDocument::where([['is_deleted',null],['emp_id',$emp_id]])->orderBy('id', 'ASC') ->get();
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

            $item->count = $key + 1;
            $item->last_updated_by = $last_updated_by;
            $item->last_updated_at = $last_updated_at;
            $item->file_type = config('document_values.document_type.'.$item->file_id);
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

        $request = $rq->merge(['id' => null, 'type'=>'options']);
        $document_type = (new DocumentTypeOptions)->list($rq);

        $options = [
            'document_type'=>$document_type,
        ];
        return view($components.'document-attachments', compact('employee','isRegisterEmployee','options'))->render();
    }

    // public function info(Request $rq)
    // {
    //     try {
    //         $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
    //         $query = HrisEmployeeEducation::find($id);
    //         $payload = [
    //             'school'=>$query->school,
    //             'level'=>$query->level,
    //             'degree'=>$query->degree,
    //             'honors'=>$query->honors,
    //             'units'=>$query->units,
    //             'date_from' =>Carbon::parse($query->date_from)->format('m-d-Y'),
    //             'date_to' =>Carbon::parse($query->date_to)->format('m-d-Y'),
    //             'year_graduate'=>$query->year_graduate,
    //         ];
    //         return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);

    //     } catch (\Exception $e) {
    //         return [ 'status' => 'error', 'message' => $e->getMessage() ];
    //     }
    // }

    public function update(Request $rq)
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->emp_id);
            $file_type_id = $rq->file_type;
            $filePath = null;

            $emp_no = preg_replace('/[^A-Za-z0-9]/', '',Employee::find($emp_id)->emp_no);
            $document = preg_replace('/\s+/', '',config('document_values.document_type.'.$file_type_id));

            // example : EMP00010_TranscriptofRecord_pdf
            $filename = $emp_no.'_'.$document.'.'.$rq->file('files')->getClientOriginalExtension();
            $filePath = $rq->file('files')->storeAs('employee/'.$emp_no.'/documents',$filename,'public');

            if (Storage::disk('public')->exists($filePath)) {
                HrisEmployeeDocument::create([
                    'emp_id' => $emp_id,
                    'file_id' => $file_type_id,
                    'filename' => $filename,
                    'created_by'=>$user_id
                ]);
            }
            DB::commit();
            return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            Storage::disk('public')->delete($filePath);
            return [ 'status' => 'error', 'message' => $e->getMessage(), 'payload'=>'' ];
        }

    }

    public function delete(Request $rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeDocument::find($id);

            $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
            $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;
            if (Storage::disk('public')->exists($filePath)) {
                if (Storage::disk('public')->delete($filePath)) {
                    $query->delete();
                    DB::commit();
                    return [
                        'status' => 'success',
                        'message'=>'Document is removed',
                        'payload' => HrisEmployeeDocument::where([['is_deleted',null],['emp_id',$query->emp_id]])->count()
                    ];
                }
            }

            DB::rollback();
            return [ 'status' => 'error','message'=>'Something went wrong, try again later', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function download_document(Request $rq)
    {
        try {
        $id = Crypt::decrypt($rq->id);
        $query = HrisEmployeeDocument::find($id);

        $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
        $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found']);
        }

        $downloadUrl = asset('storage/'.$filePath);
        return ['status' => 'success', 'payload' => $downloadUrl];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

    public function view_document(Request $rq)
    {
        try {
        $id = Crypt::decrypt($rq->id);
        $query = HrisEmployeeDocument::find($id);

        $emp_no = preg_replace('/[^A-Za-z0-9]/', '',$query->employee->emp_no);
        $filePath = 'employee/'.$emp_no.'/documents/'.$query->filename;

        // Check if file exists in storage
        if (!Storage::disk('public')->exists($filePath)) {
            return response()->json(['status' => 'error', 'message' => 'File not found']);
        }

        $downloadUrl = asset('storage/'.$filePath);
        return ['status' => 'success', 'payload' => $downloadUrl];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }

}
