<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipClassAssessmentResult extends Model
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
        'student_mark',
        'total_mark',
        'score',
        'flip_class_assessment_id',
        'week'
    ];
}
