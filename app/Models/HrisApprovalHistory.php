<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisApprovalHistory extends Model
{
    use HasFactory;
    protected $fillable = [
        'entity_id',
        'entity_table',
        'emp_id',
        'is_approved',
        'approver_level',
        'approver_remarks',
        'is_final_approver',
        'created_by',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];

    public function employee(){
        return $this->belongsTo(Employee::class,'emp_id');
    }

}
