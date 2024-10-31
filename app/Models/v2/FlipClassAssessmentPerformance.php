<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipClassAssessmentPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'flip_class_assessment_id',
        'student_id',
        'subject_id',
        'question_type',
        'total_mark',
        'percentage_score',
        'week'
    ];

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
