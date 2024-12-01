<?php

namespace App\Models;

use App\Http\Controllers\AdminController\Settings\LeaveType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisLeaveSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'leave_type_id',
        'credit_type',
        'start_credit',
        'is_carry_over',
        'fiscal_year',
        'assign_type',
        'increment_milestones',
        'succeeding_year',
        'is_reset',
        'reset_month',
        'reset_day',
        'status',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
        'is_deleted',
        'deleted_at',
        'deleted_by',
        'classification_id',
        'employment_id',
        'location_id',
    ];

    public function leave_type(){
        return $this->belongsTo(HrisLeaveType::class,'leave_type_id')->withDefault();
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
