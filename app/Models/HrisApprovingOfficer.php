<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisApprovingOfficer extends Model
{
    use HasFactory;
    protected $fillable = [
        'emp_id',
        'company_id',
        'company_location_id',
        'department_id',
        'section_id',
        'remarks',
        'approver_level',
        'is_final_approver',
        'is_required',
        'is_active',
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
        return $this->BelongsTo(Employee::class,'emp_id')->withDefault();
    }

    public function section()
    {
        return $this->belongsTo(HrisSection::class,'section_id')->withDefault();
    }

    public function position()
    {
        return $this->belongsTo(HrisPosition::class,'position_id')->withDefault();
    }

    public function department()
    {
        return $this->belongsTo(HrisDepartment::class,'department_id')->withDefault();
    }

    public function company()
    {
        return $this->belongsTo(HrisCompany::class,'company_id')->withDefault();
    }

    public function company_location()
    {
        return $this->belongsTo(HrisCompanyLocation::class,'company_location_id')->withDefault();
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

    public function approving_history(){
        return $this->hasMany(HrisApprovalHistory::class, 'emp_id', 'emp_id');
    }
}

