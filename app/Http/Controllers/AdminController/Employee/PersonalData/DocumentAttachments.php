<?php

namespace App\Http\Controllers\AdminController\Employee\PersonalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentAttachments extends Controller
{
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

    public function update_document_attachments($rq)
    {
        try {
            DB::beginTransaction();

            $user_id = Auth::user()->emp_id;
            $emp_id = Crypt::decrypt($rq->id);
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

    public function delete_document_attachments($rq)
    {
        try {
            DB::beginTransaction();

            $id = Crypt::decrypt($rq->id);
            $query = HrisEmployeeDocument::find($id);

            $filePath = 'employee/documents/'.$query->filename;
            if (Storage::disk('public')->exists($filePath)) {
                if (Storage::disk('public')->delete($filePath)) {
                    $query->delete();
                    DB::commit();
                    return [ 'status' => 'success','message'=>'Update is success', 'payload' => ''];
                }
            }

            DB::rollback();
            return [ 'status' => 'error','message'=>'Something went wrong, try again later', 'payload' => ''];
        } catch (\Exception $e) {
            DB::rollback();
            return [ 'status' => 'error', 'message' => $e->getMessage() ];
        }
    }
}
