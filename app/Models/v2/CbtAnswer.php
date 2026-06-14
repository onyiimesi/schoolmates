<?php

namespace App\Models\v2;

use App\Models\Student;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $cbt_question_id
 * @property string $student_id
 * @property string $subject_id
 * @property string $question
 * @property string $question_number
 * @property string $question_type
 * @property string $answer
 * @property string $correct_answer
 * @property string $mark_status
 * @property string $submitted
 * @property string|null $submitted_time
 * @property string $duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\CbtQuestion $cbtquestion
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\CbtResult> $cbtresult
 * @property-read int|null $cbtresult_count
 * @property-read Student|null $student
 * @property-read Subject|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereCbtQuestionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereMarkStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereSubmittedTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'cbt_question_id',
    'student_id',
    'subject_id',
    'question',
    'question_number',
    'question_type',
    'answer',
    'correct_answer',
    'mark_status',
    'submitted',
    'submitted_time',
    'duration'
])]
class CbtAnswer extends Model
{
    use HasFactory;

    public function cbtquestion()
    {
        return $this->belongsTo(CbtQuestion::class, 'cbt_question_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function cbtresult()
    {
        return $this->hasMany(CbtResult::class, 'cbt_answer_id');
    }
}
