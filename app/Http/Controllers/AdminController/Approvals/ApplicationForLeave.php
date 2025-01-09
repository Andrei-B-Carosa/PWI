<?php

namespace App\Http\Controllers\AdminController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeLeaveRequest;
use App\Models\HrisStoredProcedure;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class ApplicationForLeave extends Controller
{
    public function dt(Request $rq)
    {
        $data = HrisStoredProcedure::sp_get_leave_request();

        $data->transform(function ($item, $key) {
            $item->count = $key + 1;
            $item->leave_filing_date = Carbon::parse($item->leave_filing_date)->format('F d, Y');
            $item->leave_date_from = Carbon::parse($item->leave_date_from)->format('h:i A');
            $item->leave_date_to = Carbon::parse($item->leave_date_to)->format('h:i A');

            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });


        $table = new DTServerSide($rq, $data);
        $table->renderTable();

        return response()->json([
            'draw' => $table->getDraw(),
            'recordsTotal' => $table->getRecordsTotal(),
            'recordsFiltered' => $table->getRecordsFiltered(),
            'data' => $table->getRows(),
        ]);
    }

    public function view_history(Request $rq)
    {
        try{
            $id = isset($rq->id) && $rq->id != "undefined" ? Crypt::decrypt($rq->id):false;
            if(!$id)
            {
                return response()->json(['status' => 'error','message'=>'Missing ID in Request']);
            }

            $applicationForLeave = HrisEmployeeLeaveRequest::find($id);
            $applicationForLeaveHistory = HrisApprovalHistory::with('employee')->where([['entity_id',$id],['entity_table',2]])->get();
            if(!$applicationForLeaveHistory)
            {
                return response()->json(['status' => 'error','message'=>'Leave Request Not Found']);
            }

            $history[] = [
                'is_approved' => 'pending',
                'action' => $applicationForLeave->employee->fullname().' filed a leave request',
                'recorded_at'  => Carbon::parse($applicationForLeave->created_at)->format('M d, Y H:i A')
            ];

            if($applicationForLeaveHistory->isNotEmpty())
            {
                foreach($applicationForLeaveHistory as $data){
                    $action = '<span class="text-success fw-bold">Approved</span>'.' the leave request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the leave request';
                        $is_approved = 'rejected';
                    }

                    $history[]=[
                        'is_approved' => $is_approved,
                        'action' => $data->employee->fullname().' '.$action,
                        'approver_remarks' =>$approver_remarks,
                        'approver_level' =>$approver_level,
                        'is_final_approver' =>$is_final_approver,
                        'recorded_at' =>Carbon::parse($data->created_at)->format('M d, Y h:i A')
                    ];
                }
            }

            $payload =base64_encode(json_encode($history));
            return response()->json(['status' => 'success','message'=>'success', 'payload'=>$payload]);
        }catch(Exception $e){
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
