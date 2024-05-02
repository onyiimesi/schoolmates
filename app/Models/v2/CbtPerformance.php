<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtPerformance extends Model
{
    protected $table = "cbt_performances";

    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'cbt_result_id',
        'student_id',
        'subject_id',
        'question_type',
        'student_total_mark',
        'test_total_mark',
        'student_duration',
        'test_duration',
        'correct_answer',
        'incorrect_answer',
        'total_answer'
    ];

    public function cbtresult()
    {
        return $this->belongsTo(CbtResult::class, 'cbt_result_id');
    }
}
