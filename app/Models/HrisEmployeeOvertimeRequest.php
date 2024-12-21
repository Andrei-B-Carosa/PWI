<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeOvertimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id' ,
        'overtime_date' ,
        'overtime_from',
        'overtime_to' ,
        'reason',
        'is_approved',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class,'emp_id');
    }

    public function employee_position()
    {
        return $this->hasOne(HrisEmployeePosition::class,'emp_id','emp_id');
    }

    public function latest_approval_histories()
    {
        return $this->hasOne(HrisApprovalHistory::class,'entity_id')->where('entity_table', 1)->latest();
    }

    public function group_member()
    {
        return $this->hasOne(HrisGroupMember::class,'emp_id','emp_id')->where('is_active',1);
    }

    public function approving_history(){
        return $this->hasMany(HrisApprovalHistory::class, 'entity_id')->where('entity_table', 1);
    }
}
