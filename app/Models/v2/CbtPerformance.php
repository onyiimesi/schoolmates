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
 * @property int $cbt_result_id
 * @property string $student_id
 * @property string $subject_id
 * @property string $question_type
 * @property string $student_total_mark
 * @property string $correct_answer
 * @property string $incorrect_answer
 * @property string $unattempted_question
 * @property string $total_answer
 * @property string $test_total_mark
 * @property string $student_duration
 * @property string $test_duration
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\CbtResult|null $cbtresult
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereCbtResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereIncorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereStudentDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereStudentTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereTestDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereTestTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereTotalAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereUnattemptedQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtPerformance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'cbt_result_id',
    'student_id',
    'subject_id',
    'question_type',
    'student_total_mark',
    'test_total_mark',
    'student_duration',
    'test_duration',
    'correct_answer',
    'incorrect_answer',
    'total_answer',
    'unattempted_question'
])]
#[\Illuminate\Database\Eloquent\Attributes\Table(name: "cbt_performances")]
class CbtPerformance extends Model
{
    use HasFactory;

    public function cbtresult()
    {
        return $this->belongsTo(CbtResult::class, 'cbt_result_id');
    }
}
