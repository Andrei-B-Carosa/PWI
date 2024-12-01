<?php

namespace App\Services\Reusable\Select;

use App\Models\HrisEmployeeOvertimeRequest;
use App\Models\HrisLeaveType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class LeaveFiscalYearOptions
{

    public function list(Request $rq)
    {
        $data = config('leave_values.fiscal_year');
        return match($rq->type){
            'options' => $this->options($rq,$data),
        };
    }


    public function options($rq,$data)
    {
        $search = isset($rq->id) ? Crypt::decrypt($rq->id) : false;
        if ($data) {
            $html = '<option></option>';
            foreach ($data as $key => $row) {
                $selected = $search == $key ? 'selected' : '';
                $html .= '<option value="'.e($key).'"'.e($selected).'>'.e($row).'</option>';
            }
            return $html;
        } else {
            return '<option disabled>No Available Option</option>';
        }
    }
}
