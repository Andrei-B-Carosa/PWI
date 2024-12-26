<?php

namespace App\Http\Controllers\AdminController\Approvals;

use App\Http\Controllers\Controller;
use App\Models\HrisStoredProcedure;
use App\Services\Reusable\DTServerSide;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class OfficialBusiness extends Controller
{
    public function dt(Request $rq)
    {
        $data = HrisStoredProcedure::sp_get_ob_request();

        $data->transform(function ($item, $key) {
            $item->count = $key + 1;
            $item->ob_filing_date = Carbon::parse($item->ob_filing_date)->format('F d, Y');
            $item->ob_time_out = Carbon::parse($item->ob_time_out)->format('h:i A');
            $item->ob_time_in = Carbon::parse($item->ob_time_in)->format('h:i A');
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

        // $filter_status = $rq->filter_status != 'all' ? $rq->filter_status : false;
        // $filter_month = $rq->filter_month ?? false;
        // $filter_year = $rq->filter_year ?? false;

        // $data = HrisEmployeeOvertimeRequest::with('latest_approval_histories')
        // ->where('is_deleted',null)
        // ->when($filter_status, function ($q) use ($filter_status) {
        //     $status = [
        //         'pending'=>null,
        //         'approved'=>1,
        //         'disapproved'=>2,
        //     ];
        //     $q->where('is_approved', $status[$filter_status]);
        // })
        // ->when($filter_month, function ($q) use ($filter_month) {
        //     $q->whereRaw('MONTH(overtime_date) = ?', [$filter_month]);
        // })
        // ->when($filter_year, function ($q) use ($filter_year) {
        //     $q->whereRaw('YEAR(overtime_date) = ?', [$filter_year]);
        // })
        // ->orderBy('id', 'ASC')
        // ->get();

        // $data->transform(function ($item, $key) {

        //     // $last_updated_by = null;
        //     // if($item->updated_by != null){
        //     //     $last_updated_by = $item->updated_by_emp->fullname();
        //     // }elseif($item->created_by !=null){
        //     //     $last_updated_by = $item->created_by_emp->fullname();
        //     // }

        //     $approver = $item->latest_approval_histories;
        //     $approved_by = null;
        //     $approver_level = null;
        //     $approver_remarks = null;
        //     if($item->latest_approval_histories){
        //         $approved_by = $approver->employee->fullname();
        //         $approver_level = $approver->approver_level;
        //         $approver_remarks = $approver->approver_remarks;
        //     }

        //     $item->count = $key + 1;
        //     $item->overtime_date = Carbon::parse($item->overtime_date)->format('m/d/Y');
        //     $item->overtime_from = Carbon::parse($item->overtime_from)->format('h:i A');
        //     $item->overtime_to = Carbon::parse($item->overtime_to)->format('h:i A');

        //     $item->approved_by = $approved_by;
        //     $item->approver_level = $approver_level;
        //     $item->approver_remarks = $approver_remarks;
        //     $item->encrypted_id = Crypt::encrypt($item->id);

        //     return $item;
        // });

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
