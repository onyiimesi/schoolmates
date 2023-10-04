<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type', 'question', 'answer', 'subject_id', 'student_id', 'correct_answer', 'mark', 'sch_id', 'campus', 'session', 'period', 'term', 'assignment_id', 'submitted', 'question_number', 'week'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
