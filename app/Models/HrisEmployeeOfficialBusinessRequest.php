<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisEmployeeOfficialBusinessRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'emp_id',
        'ob_filing_date',
        'estimated_ob_time_out',
        'estimated_ob_time_in',
        'actual_ob_time_out',
        'actual_ob_time_in',
        'guard_id',
        'guard_remarks',
        'destination',
        'contact_person_id',
        'is_approved',
        'purpose',
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
        return $this->hasOne(HrisApprovalHistory::class,'entity_id')->where('entity_table', 3)->latest();
    }

    public function approving_history(){
        return $this->hasMany(HrisApprovalHistory::class, 'entity_id')->where('entity_table', 3);
    }

    public function emp_contact_person()
    {
        return $this->belongsTo(Employee::class,'contact_person_id');
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

    public function group_member()
    {
        return $this->hasOne(HrisGroupMember::class,'emp_id','emp_id')->where('is_active',1);
    }
}
