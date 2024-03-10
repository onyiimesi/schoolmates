<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_type',
        'question',
        'answer',
        'subject_id',
        'option1',
        'option2',
        'option3',
        'option4',
        'image',
        'sch_id',
        'campus',
        'session',
        'teacher_id',
        'period',
        'term',
        'total_question',
        'question_mark',
        'total_mark',
        'question_number',
        'week',
        'status'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }
}
