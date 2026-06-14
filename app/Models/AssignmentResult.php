<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $assignment_id
 * @property string $student_id
 * @property int|null $subject_id
 * @property int|null $subject_class_id
 * @property string $question_type
 * @property string $student_mark
 * @property string $total_mark
 * @property string $score
 * @property string|null $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SubjectClass|null $subjectClass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereStudentMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereSubjectClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentResult whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'student_id',
    'subject_id',
    'question_type',
    'student_mark',
    'total_mark',
    'score',
    'assignment_id',
    'week'
])]
class AssignmentResult extends Model
{
    use HasFactory;

    public function subjectClass()
    {
        return $this->belongsTo(SubjectClass::class);
    }
}
