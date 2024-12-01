<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeEducation extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id' ,
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];
}
