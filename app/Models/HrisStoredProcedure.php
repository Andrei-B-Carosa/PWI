<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class HrisStoredProcedure extends Model
{
    use HasFactory;

    public static function sp_get_overtime_request(){
        return static::hydrate(
            DB::select('call sp_get_overtime_request()')
        );
    }

    public static function sp_get_ob_request(){
        return static::hydrate(
            DB::select('call sp_get_ob_request()')
        );
    }

    public static function sp_get_leave_request(){
        return static::hydrate(
            DB::select('call sp_get_leave_request()')
        );
    }

    public static function sp_get_approval_history($emp_id){
        return static::hydrate(
            DB::select('call sp_get_approval_history(?)',[$emp_id])
        );
    }
}
