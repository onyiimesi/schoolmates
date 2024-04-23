<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'cbt_setting_id',
        'subject_id',
        'question',
        'question_type',
        'option1',
        'option2',
        'option3',
        'option4',
        'answer',
        'question_mark',
        'total_mark',
        'question_number',
        'total_question',
        'teacher_id',
        'status'
    ];
}
