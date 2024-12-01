<?php

namespace App\Services\Reusable\Select;

use DateTime;
use Illuminate\Http\Request;

class MonthsDayOptions
{

    public function list(Request $rq)
    {
        return match($rq->type){
            'options' => $this->options($rq),
        };
    }


    public function options($rq)
    {
        $month = '<option></option>';
        for ($i = 1; $i <= 12; $i++) {
            $monthName = DateTime::createFromFormat('!m', $i)->format('F');
            $month .= '<option value="' . e($i) . '">' . e($monthName) . '</option>';
        }

        $day = '<option></option>';
        for ($i = 1; $i <= 31; $i++) {
            $day .= '<option value="' . e($i) . '">' . e($i) . '</option>';
        }

        return [$month,$day] ;
    }


}
