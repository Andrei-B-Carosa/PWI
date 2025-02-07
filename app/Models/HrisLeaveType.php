<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisLeaveType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name' ,
        'description',
        'code',
        'company_id',
        'company_location_id',
        'is_active',
        'gender_type',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
    ];

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

    public function company()
    {
        return $this->belongsTo(HrisCompany::class,'company_id')->withDefault();
    }

    public function company_location()
    {
        return $this->belongsTo(HrisCompanyLocation::class,'company_location_id')->withDefault();
    }

    public function leave_setting()
    {
        return $this->hasOne(HrisLeaveSetting::class,'leave_type_id');
    }
}
