<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HrisSystemFile extends Model
{
    use HasFactory;

    public function file_layer(){
        return $this->hasMany(HrisSystemFileLayer::class,'file_id','id');
    }
}
