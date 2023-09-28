<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentMark extends Model
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
        'question_id',
        'submitted',
        'question_number',
        'teacher_mark'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'question_id');
    }
}
