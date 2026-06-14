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
 * @property string $question
 * @property string $question_number
 * @property string $question_type
 * @property string $answer
 * @property string $correct_answer
 * @property string $mark
 * @property string $submitted
 * @property string|null $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\Student|null $student
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\SubjectClass|null $subjectClass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereSubjectClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentAnswer whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'question_type', 'question', 'answer', 'subject_id', 'student_id', 'correct_answer', 'mark', 'sch_id', 'campus', 'session', 'period', 'term', 'assignment_id', 'submitted', 'question_number', 'week'
])]
class AssignmentAnswer extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function subjectClass()
    {
        return $this->belongsTo(SubjectClass::class);
    }

    public function assignment()
    {
        return $this->belongsTo(Assignment::class, 'assignment_id');
    }
}
