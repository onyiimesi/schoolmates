<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property int $class_id
 * @property string|null $class_name
 * @property string $subject
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentAnswer> $assignmentAnswers
 * @property-read int|null $assignment_answers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentMark> $assignmentMarks
 * @property-read int|null $assignment_marks_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentPerformance> $assignmentPerformances
 * @property-read int|null $assignment_performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentResult> $assignmentResults
 * @property-read int|null $assignment_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Assignment> $assignments
 * @property-read int|null $assignments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Subject|null $subjects
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SubjectClass whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'term',
    'session',
    'class_id',
    'class_name',
    'subject'
])]
class SubjectClass extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function subjects()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignmentResults()
    {
        return $this->hasMany(AssignmentResult::class);
    }

    public function assignmentAnswers()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    public function assignmentMarks()
    {
        return $this->hasMany(AssignmentMark::class);
    }

    public function assignmentPerformances()
    {
        return $this->hasMany(AssignmentPerformance::class);
    }
}
