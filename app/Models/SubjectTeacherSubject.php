<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubjectTeacherSubject extends Model
{
    protected $fillable = [
        'subject_teacher_id',
        'subject_name',
        'staff_id',
        'class_id',
        'sch_id',
        'campus',
        'term',
        'session',
    ];

    public function subjectTeacher(): BelongsTo
    {
        return $this->belongsTo(SubjectTeacher::class);
    }
}
