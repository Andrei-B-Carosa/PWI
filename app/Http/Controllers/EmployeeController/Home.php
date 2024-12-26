<?php

namespace App\Http\Controllers\EmployeeController;

use App\Http\Controllers\Controller;
use App\Models\HrisApprovalHistory;
use App\Models\HrisEmployeeLeaveBalance;
use App\Models\HrisEmployeeLeaveRequest;
use App\Models\HrisEmployeeOfficialBusinessRequest;
use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisGroup;
use App\Models\HrisGroupApprover;
use App\Models\HrisGroupMember;
use App\Models\HrisStoredProcedure;
use App\Services\Reusable\DTServerSide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Home extends Controller
{
    public function leave_credit()
    {
        $emp_id = Auth::user()->emp_id;
        $leave_balance = HrisEmployeeLeaveBalance::with('leave_type')->where([['emp_id',$emp_id],['is_active',1]])->get();

        $data = [];
        foreach($leave_balance as $leave){
            $data[] = [
                'name'=>$leave->leave_type->name,
                'code'=>$leave->leave_type->code,
                'balance'=>$leave->leave_balance,
            ];
        }

        $payload = base64_encode(json_encode($data));
        return response()->json([
            'status'=>'success',
            'message'=>'success',
            'payload'=> $payload
        ]);
    }

    public function group_details()
    {
        $emp_id = Auth::user()->emp_id;

        $group_id = HrisGroupMember::where([['emp_id',$emp_id],['is_active',1]])->value('group_id');
        if (!$group_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee does not belong to any group.',
                'payload'=>''
            ]);
        }

        $group = HrisGroup::find($group_id);
        $group_member = HrisGroupMember::where([['group_id',$group_id],['is_active',1]])->limit(5)->get();
        $group_approver = HrisGroupApprover::where([['group_id',$group_id],['is_active',1]])->orderBy('is_final_approver', 'ASC')
        ->orderBy('approver_level', 'DESC')->get();

        $data=[
            'group_member'=>[],
            'group_approver'=>[],
            'group_details'=>[
                'name'=>$group->name,
                'description'=>$group->description ?? 'No description available',
            ],
        ];

        foreach($group_member as $groupMember){
            $name = $groupMember->employee->fullname();
            if($groupMember->emp_id == $emp_id){
                $name = $name.' (You)';
            }
            $data['group_member'][]=[
                'name'=>$name,
                'position'=>$groupMember->employee->emp_details->position->name
            ];
        }

        foreach($group_approver as $groupApprover){
            $name = $groupApprover->employee->fullname();
            $data['group_approver'][]=[
                'name'=>$name,
                'is_final_approver'=>$groupApprover->is_final_approver,
                'approver_level'=>$groupApprover->approver_level,
                'is_required'=>$groupApprover->is_required,
            ];
        }

        $payload = base64_encode(json_encode($data));
        return response()->json([
            'status'=>'success',
            'message'=>'success',
            'payload'=> $payload
        ]);
    }

    public function get_group_members(Request $rq)
    {
        $emp_id = Auth::user()->emp_id;
        $offset = $rq->input('offset', 0);
        $limit = $rq->input('limit', 5);

        $group_id = HrisGroupMember::where([['emp_id',$emp_id],['is_active',1]])->value('group_id');
        if (!$group_id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Employee does not belong to any group.',
                'payload'=>''
            ]);
        }

        $group = HrisGroup::find($group_id);
        $group_member = HrisGroupMember::where([['group_id',$group_id],['is_active',1]]
        )->skip($offset)
        ->take($limit)->get();

        $data=[];
        foreach($group_member as $groupMember){
            $name = $groupMember->employee->fullname();
            if($groupMember->emp_id == $emp_id){
                $name = $name.' (You)';
            }
            $data['group_member'][]=[
                'name'=>$name,
                'position'=>$groupMember->employee->emp_details->position->name
            ];
        }

        $payload = base64_encode(json_encode($data));
        return response()->json([
            'status'=>'success',
            'message'=>'success',
            'payload'=> $payload
        ]);
    }

    public function approval_history(Request $rq)
    {

        $emp_id = Auth::user()->emp_id;
        $data = HrisStoredProcedure::sp_get_approval_history($emp_id);

        $data->transform(function ($item, $key) use($emp_id) {
            $item->count = $key + 1;
            $item->encrypted_id = Crypt::encrypt($item->entity_id);
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
}
