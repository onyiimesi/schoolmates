<?php

namespace App\Models\v2;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'cbt_question_id',
        'student_id',
        'subject_id',
        'question',
        'question_number',
        'question_type',
        'answer',
        'correct_answer',
        'mark_status',
        'submitted',
        'submitted_time',
        'duration'
    ];

    public function cbtquestion()
    {
        return $this->belongsTo(CbtQuestion::class, 'cbt_question_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
