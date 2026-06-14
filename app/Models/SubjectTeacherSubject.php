<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property int $subject_teacher_id
 * @property string $term
 * @property string $session
 * @property string $subject_name
 * @property int $staff_id
 * @property int $class_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SubjectTeacher $subjectTeacher
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereSubjectName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereSubjectTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectTeacherSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'subject_teacher_id',
    'subject_name',
    'staff_id',
    'class_id',
    'sch_id',
    'campus',
    'term',
    'session',
])]
class SubjectTeacherSubject extends Model
{
    public function subjectTeacher(): BelongsTo
    {
        return $this->belongsTo(SubjectTeacher::class);
    }
}
