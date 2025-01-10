<?php

namespace App\Http\Controllers\EmployeeController\Request;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeOfficialBusinessRequest as OBRequest;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Services\Reusable\DTServerSide;
use App\Services\Reusable\GroupApproverNotification;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OfficialBusiness extends Controller
{
    public function dt(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        $filter_month = $rq->filter_month ?? false;
        $filter_year = $rq->filter_year ?? false;

        $data = OBRequest::with(['latest_approval_histories','emp_contact_person'])
        ->where([['emp_id',$emp_id],['is_deleted',null]])
        ->when($filter_status, function ($q) use ($filter_status) {
            $status = [
                'pending'=>null,
                'approved'=>1,
                'disapproved'=>2,
            ];
            $q->where('is_approved', $status[$filter_status]);
        })
        ->when($filter_month, function ($q) use ($filter_month) {
            $q->whereRaw('MONTH(ob_filing_date) = ?', [$filter_month]);
        })
        ->when($filter_year, function ($q) use ($filter_year) {
            $q->whereRaw('YEAR(ob_filing_date) = ?', [$filter_year]);
        })
        ->orderBy('id', 'ASC')
        ->get();

        $data->transform(function ($item, $key) {

            // $last_updated_by = null;
            // if($item->updated_by != null){
            //     $last_updated_by = $item->updated_by_emp->fullname();
            // }elseif($item->created_by !=null){
            //     $last_updated_by = $item->created_by_emp->fullname();
            // }
            $approver = $item->latest_approval_histories;
            $approved_by = null;
            $approver_level = null;
            $approver_remarks = null;
            $approver_status = null;
            $approver_type = null;
            if($approver){
                $approved_by = $approver->employee->fullname();
                $approver_level = 'Level '.$approver->approver_level;
                $approver_remarks = $approver->approver_remarks;
                $approver_status = $approver->is_approved;
                $approver_type = $approver->approver_type == 1?'Department-Level':'Section-Level';
            }

            $item->count = $key + 1;
            $item->ob_filing_date = Carbon::parse($item->ob_filing_date)->format('m/d/Y');
            $item->ob_time_out = Carbon::parse($item->estimated_ob_time_out)->format('h:i A');
            $item->ob_time_in = Carbon::parse($item->estimated_ob_time_in)->format('h:i A');

            // $item->contact_person_name = $contact_person_name;
            // $item->contact_person_number = $contact_person_number;

            $item->approved_by = $approved_by;
            $item->approver_level = $approver_level;
            $item->approver_remarks = $approver_remarks;
            $item->approver_status = $approver_status;
            $item->approver_type = $approver_type;

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

    public function update(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):null;
            $obFillingDate = Carbon::now();
            $obTimeIn = Carbon::createFromFormat('H:i', $rq->ob_time_in)->format('H:i');
            $obTimeOut = Carbon::createFromFormat('H:i', $rq->ob_time_out)->format('H:i');
            $obContactPerson = isset($rq->contact_person) ? Crypt::decrypt($rq->contact_person):null;
            $isResubmit = false;

            $attribute = ['id'=>$id];
            $values = [
                'estimated_ob_time_out' => $obTimeOut,
                'estimated_ob_time_in' => $obTimeIn,
                'destination' => $rq->destination,
                'contact_person_id' => $obContactPerson,
                'purpose' => $rq->purpose,
            ];
            if($id == null){
                $values['emp_id'] = $user_id;
                $values['ob_filing_date'] = $obFillingDate;
                $values['created_by']= $user_id;
                $message = 'File successfully';
            }else{
                $values['updated_by']= $user_id;
                $message = 'Details is updated';
            }

            $query = OBRequest::updateOrCreate($attribute,$values);
            if($query->is_approved == 2){
                $query->update([ 'is_approved'=> null ]);
                $isResubmit = true;
            }
            $isNotified = (new GroupApproverNotification)
                ->sendApprovalNotification($query,3,'approver.ob_request',$isResubmit);
            if($isNotified){
                DB::commit();
                return response()->json(['status' => 'success','message'=>$message]);
            }else{
                DB::rollback();
                return response()->json([
                    'status' => 'error',
                    'message'=>'Something went wrong, try again later',
                    // 'message' => $e->getMessage(),
                ]);
            }

        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function info(Request $rq)
    {
        try{
            $id = Crypt::decrypt($rq->id);
            $query = OBRequest::find($id);

            $payload = [
                'ob_filing_date' =>Carbon::parse($query->ob_filing_date)->format('m-d-Y'),
                'ob_time_out' =>Carbon::parse($query->estimated_ob_time_out)->format('H:i'),
                'ob_time_in' =>Carbon::parse($query->estimated_ob_time_in)->format('H:i'),
                'destination' =>$query->destination,
                'purpose' =>$query->purpose,
                'contact_person_id' =>optional($query->emp_contact_person)->fullname() ?? null,
            ];

            return response()->json(['status' => 'success','message'=>'success', 'payload'=>base64_encode(json_encode($payload))]);
        }catch(Exception $e){
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function delete(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;
            $id =  Crypt::decrypt($rq->id);

            $query = OBRequest::find($id);
            $query->is_deleted = 1;
            $query->deleted_by = $user_id;
            $query->deleted_at = Carbon::now();
            $query->save();

            DB::commit();
            return response()->json([
                'status' => 'info',
                'message'=>'OB request is deleted',
                'payload'=> OBRequest::where([['emp_id',$user_id],['is_deleted',null]])->count()
            ]);
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

            $check_date_request = $this->check_date_request($rq);
            if(!$check_date_request['valid']){
                return response()->json($check_date_request);
            }

            return ['valid' => true, 'message' => 'Eligible for filing ob'];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function check_date_request(Request $rq)
    {
        $excluded_id = isset($rq->id) && $rq->id !== "undefined" ? Crypt::decrypt($rq->id) : false;

        // Parse and format the time and date
        $obTimeOut = Carbon::createFromFormat('H:i', $rq->ob_time_out)->format('H:i');
        $obTimeIn = Carbon::createFromFormat('H:i', $rq->ob_time_in)->format('H:i');
        $obFilingDate = Carbon::createFromFormat('m-d-Y', $rq->ob_filing_date)->format('Y-m-d');

        // Query to check if there is any overlapping OB request
        $exist = OBRequest::where('emp_id', Auth::user()->emp_id)
            ->where([['is_deleted', null],['ob_filing_date', $obFilingDate]])
            ->where(function ($query) use ($obTimeOut, $obTimeIn) {
                // Check for overlapping time ranges
                $query->whereBetween('estimated_ob_time_out', [$obTimeOut, $obTimeIn])
                      ->orWhereBetween('estimated_ob_time_in', [$obTimeOut, $obTimeIn])
                      ->orWhere(function ($subQuery) use ($obTimeOut, $obTimeIn) {
                          $subQuery->where('estimated_ob_time_out', '<', $obTimeIn)
                                   ->where('estimated_ob_time_in', '>', $obTimeOut);
                      });
            })
            ->when($excluded_id, function ($query) use ($excluded_id) {
                // Exclude the current request if updating
                $query->where('id', '!=', $excluded_id);
            })
            ->exists();

        return [
            'valid' => !$exist,
            'message' => 'There is a OB request that overlaps with the specified date and time.'
        ];
    }

    public function widgets()
    {
        try{
            $user_id = Auth::user()->emp_id;
            $results = OBRequest::selectRaw("
                COUNT(*) as total_requests,
                SUM(CASE WHEN is_approved IS NULL THEN 1 ELSE 0 END) as pending_requests,
                SUM(CASE WHEN is_approved = 1 THEN 1 ELSE 0 END) as approved_requests,
                SUM(CASE WHEN is_approved = 2 THEN 1 ELSE 0 END) as rejected_requests
            ")
            ->where('emp_id', $user_id)
            ->where('is_deleted',null)
            ->first();

            return [
                'valid' => 'success',
                'message' => 'success',
                'payload' => [
                    'total_requests' => $results->total_requests ??0,
                    'pending_requests' => $results->pending_requests??0,
                    'approved_requests' => $results->approved_requests??0,
                    'rejected_requests' => $results->rejected_requests??0,
                ]
            ];

        }catch(Exception $e)
        {
            return response()->json(['status'=>400,'message' =>$e->getMessage()]);
        }
    }

    public function view_history(Request $rq)
    {
        try{
            DB::beginTransaction();
            $user_id = Auth::user()->emp_id;

            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):false;
            if(!$id)
            {
                return response()->json(['status' => 'error','message'=>'Missing ID in Request']);
            }

            $overtimeRequest = HrisEmployeeOfficialBusinessRequest::find($id);
            $overtimeRequestHistory = HrisApprovalHistory::with('employee')->where([['entity_id',$id],['entity_table',3]])->get();
            if(!$overtimeRequestHistory)
            {
                return response()->json(['status' => 'error','message'=>'OB Request Not Found']);
            }

            $history[] = [
                'is_approved' => 'pending',
                'action' => 'You filed a OB request',
                'recorded_at'  => Carbon::parse($overtimeRequest->created_at)->format('M d, Y h:i A')
            ];

            if($overtimeRequestHistory->isNotEmpty())
            {
                foreach($overtimeRequestHistory as $data){
                    $action = '<span class="text-success fw-bold">Approved</span>'.' the OB request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the OB request';
                        $is_approved = 'rejected';
                    }

                    $history[]=[
                        'is_approved' => $is_approved,
                        'action' => $data->employee->fullname().' '.$action,
                        'approver_remarks' =>$approver_remarks,
                        'approver_level' =>$approver_level,
                        'is_final_approver' =>$is_final_approver,
                        'recorded_at' =>Carbon::parse($data->created_at)->format('M d, Y H:i A')
                    ];
                }
            }

            $payload =base64_encode(json_encode($history));
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>$payload]);
        }catch(Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
