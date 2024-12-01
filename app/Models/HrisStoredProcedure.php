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
}
