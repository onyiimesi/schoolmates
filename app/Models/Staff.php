<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'designation_id', 
        'department', 
        'surname', 
        'firstname', 
        'middlename', 
        'username', 
        'email', 
        'phoneno', 
        'address', 
        'image', 
        'password', 
        'pass_word', 
        'class_assigned',
        'sub_class',
        'status'
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }
}
