<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeLeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'leave_filing_date',
        'leave_date_from' ,
        'leave_date_to',
        'reason',
        'leave_type_id',
        'is_excused',
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
        return $this->hasOne(HrisApprovalHistory::class,'entity_id')->where('entity_table', 2)->latestOfMany();
    }

    public function leave_type()
    {
        return $this->belongsTo(HrisLeaveType::class,'leave_type_id');
    }

    public function updated_by_emp()
    {
        return $this->belongsTo(Employee::class,'updated_by')->withDefault();
    }

    public function created_by_emp()
    {
        return $this->belongsTo(Employee::class,'created_by')->withDefault();
    }

    public function deleted_by_emp()
    {
        return $this->belongsTo(Employee::class,'deleted_by');
    }
}
