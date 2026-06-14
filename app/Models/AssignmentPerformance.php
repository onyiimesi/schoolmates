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
 * @property string $total_mark
 * @property string $percentage_score
 * @property string $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\SubjectClass|null $subjectClass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance wherePercentageScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereSubjectClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentPerformance whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
])]
class AssignmentPerformance extends Model
{
    use HasFactory;

    public function subjectClass()
    {
        return $this->belongsTo(SubjectClass::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
