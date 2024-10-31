<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipClassAssessmentMark extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type',
        'question',
        'answer',
        'subject_id',
        'student_id',
        'correct_answer',
        'mark',
        'sch_id',
        'campus',
        'session',
        'period',
        'term',
        'flip_class_assessment_id',
        'submitted',
        'question_number',
        'teacher_mark',
        'week',
        'topic',
    ];

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
