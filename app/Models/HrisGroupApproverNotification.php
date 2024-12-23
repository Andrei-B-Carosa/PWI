<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisGroupApproverNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'entity_id',
        'entity_table',
        'emp_id',
        'approver_level',
        'request_link_token',
        'link_status',
        'link_expired_at',
        'is_approved',
        'is_required',
        'is_final_approver',
        'group_id',
        'created_by',
        'updated_by',
        'updated_at',
        'created_at',
    ];
}
