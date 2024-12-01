<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeLeaveBalance extends Model
{
    use HasFactory;

    protected $fillable=[
        'leave_type_id',
        'emp_id' ,
        'leave_balance',
        'is_active',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];
}
