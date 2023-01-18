<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'surname', 
        'firstname', 
        'middlename', 
        'admission_number', 
        'username', 
        'password', 
        'pass_word', 
        'genotype', 
        'blood_group', 
        'gender', 
        'dob', 
        'nationality', 
        'state', 
        'session_admitted', 
        'class',
        'class_sub_class',  
        'present_class',
        'sub_class',  
        'image',
        'home_address',
        'phone_number',
        'email_address',
        'status',
        'created_by'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
