<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $cbt_answer_id
 * @property string $student_id
 * @property string $subject_id
 * @property string $question_type
 * @property array<array-key, mixed> $answer_score
 * @property string $correct_answer
 * @property string $incorrect_answer
 * @property string $unattempted_question
 * @property string $total_answer
 * @property string $student_total_mark
 * @property string $test_total_mark
 * @property string $student_duration
 * @property string $test_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\CbtAnswer $cbtanswer
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\CbtPerformance> $cbtperformance
 * @property-read int|null $cbtperformance_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereAnswerScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereCbtAnswerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereIncorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereStudentDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereStudentTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereTestDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereTestTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereTotalAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereUnattemptedQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
    'test_duration',
    'correct_answer',
    'incorrect_answer',
    'total_answer',
    'unattempted_question'
])]
class CbtResult extends Model
{
    use HasFactory;

    public function cbtanswer()
    {
        return $this->belongsTo(CbtAnswer::class, 'cbt_answer_id');
    }

    public function cbtperformance()
    {
        return $this->hasMany(CbtPerformance::class, 'cbt_result_id');
    }
    protected function casts(): array
    {
        return [
            'answer_score' => 'array'
        ];
    }
}
