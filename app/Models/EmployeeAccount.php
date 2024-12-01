<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class EmployeeAccount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'emp_id',
        'username',
        'password',
        'is_active',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'bypass_key',
    ];

    public function user_roles(){
       return $this->hasOne(HrisUserRole::class,'emp_id','emp_id');
    }

    public function employee(){
        return $this->hasOne(Employee::class,'id','emp_id');
    }

    public function employee_details(){
        return $this->hasOne(HrisEmployeePosition::class,'emp_id','emp_id');
    }
}
