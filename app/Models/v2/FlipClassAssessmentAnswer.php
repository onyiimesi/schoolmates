<?php

namespace App\Models\v2;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipClassAssessmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type', 'question', 'answer', 'subject_id', 'student_id', 'correct_answer', 'mark', 'sch_id', 'campus', 'session', 'period', 'term', 'flip_class_assessment_id', 'submitted', 'question_number', 'week', 'topic'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
