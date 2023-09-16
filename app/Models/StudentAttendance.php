<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class StudentAttendance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id', 
        'student_id',
        'admission_number', 
        'student_fullname', 
        'class', 
        'period',
        'term',
        'session',
        'attendance_date',
        'status',
        'data'
    ];

    protected $casts = [
        'data' => 'array',
    ];
    

}
