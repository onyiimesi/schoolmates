<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cbt_setting_id
 * @property string|null $teacher_id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property string $class_id
 * @property string $subject_id
 * @property string $question_type
 * @property string $question
 * @property string $option1
 * @property string $option2
 * @property string $option3
 * @property string $option4
 * @property string $answer
 * @property string $question_mark
 * @property string|null $total_mark
 * @property string|null $question_number
 * @property string|null $total_question
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\CbtAnswer> $cbtanswer
 * @property-read int|null $cbtanswer_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereCbtSettingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereOption1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereOption2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereOption3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereOption4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereQuestionMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereTotalQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'class_id',
    'cbt_setting_id',
    'subject_id',
    'question',
    'question_type',
    'option1',
    'option2',
    'option3',
    'option4',
    'answer',
    'question_mark',
    'total_mark',
    'question_number',
    'total_question',
    'teacher_id',
    'status'
])]
class CbtQuestion extends Model
{
    use HasFactory;

    public function cbtanswer()
    {
        return $this->hasMany(CbtAnswer::class, 'cbt_question_id');
    }
}
