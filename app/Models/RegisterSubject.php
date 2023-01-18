<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id', 
        'admission_number', 
        'student_fullname', 
        'class', 
        'sub_class', 
        'subject', 
        'period', 
        'term', 
        'session', 
    ];
}
