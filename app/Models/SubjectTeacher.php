<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectTeacher extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'class_id',
        'staff_id',
        'class_name',
        'subject'
    ];

    protected $casts = [
        'subject' => 'array'
    ];


}
