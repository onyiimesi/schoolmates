<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CbtResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'cbt_answer_id',
        'student_id',
        'subject_id',
        'question_type',
        'answer_score',
        'student_total_mark',
        'test_total_mark',
        'student_duration',
        'test_duration'
    ];

    protected $casts = [
        'answer_score' => 'array'
    ];

    public function cbtanswer()
    {
        return $this->belongsTo(CbtAnswer::class, 'cbt_answer_id');
    }

    public function cbtperformance()
    {
        return $this->hasMany(CbtPerformance::class, 'cbt_result_id');
    }
}
