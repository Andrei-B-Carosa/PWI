<?php

namespace App\Http\Controllers\AdminController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisStoredProcedure;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class OvertimeRequisition extends Controller
{
    public function dt(Request $rq)
    {
        $data = HrisStoredProcedure::sp_get_overtime_request();

        $data->transform(function ($item, $key) {
            $item->count = $key + 1;
            $item->overtime_date = Carbon::parse($item->overtime_date)->format('F d, Y');
            $item->overtime_from = Carbon::parse($item->overtime_from)->format('h:i A');
            $item->overtime_to = Carbon::parse($item->overtime_to)->format('h:i A');

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

            $overtimeRequest = HrisEmployeeOvertimeRequest::find($id);
            $overtimeRequestHistory = HrisApprovalHistory::with('employee')->where([['entity_id',$id],['entity_table',1]])->get();
            if(!$overtimeRequestHistory)
            {
                return response()->json(['status' => 'error','message'=>'Overtime Request Not Found']);
            }

            $history[] = [
                'is_approved' => 'pending',
                'action' => $overtimeRequest->employee->fullname().' filed a overtime request',
                'recorded_at'  => Carbon::parse($overtimeRequest->created_at)->format('M d, Y h:i A')
            ];

            if($overtimeRequestHistory->isNotEmpty())
            {
                foreach($overtimeRequestHistory as $data){
                    $action = '<span class="text-success fw-bold">Approved</span>'.' the overtime request';
                    $is_approved = 'approved';
                    $approver_remarks =$data->approver_remarks;
                    $approver_level =$data->approver_level;
                    $is_final_approver =$data->is_final_approver;
                    if($data->is_approved ==2){
                        $action = '<span class="text-danger fw-bold">Rejected</span> the overtime request';
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
            return response()->json([
                'status' => 400,
                'message' => $e->getMessage(),
            ]);
        }
    }
}
