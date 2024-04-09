<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'assignment_id',
        'student_id',
        'subject_id',
        'question_type',
        'total_mark',
        'percentage_score',
        'week'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
