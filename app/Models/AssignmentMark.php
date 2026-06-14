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
 * @property string $question_type
 * @property string $question_number
 * @property string $answer
 * @property string $correct_answer
 * @property string $mark
 * @property string $teacher_mark
 * @property string $submitted
 * @property string|null $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Assignment $assignment
 * @property-read \App\Models\SubjectClass|null $subjectClass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereAssignmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereSubjectClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereTeacherMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AssignmentMark whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'question_type',
    'question',
    'answer',
    'subject_id',
    'student_id',
    'correct_answer',
    'mark',
    'sch_id',
    'campus',
    'session',
    'period',
    'term',
    'assignment_id',
    'submitted',
    'question_number',
    'teacher_mark',
    'week'
])]
class AssignmentMark extends Model
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
