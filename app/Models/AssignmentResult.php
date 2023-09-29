<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'student_id',
        'subject_id',
        'question_type',
        'question_number',
        'mark',
        'total_mark',
        'score'
    ];
}
