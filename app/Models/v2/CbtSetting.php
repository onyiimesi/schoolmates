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
 * @property string $subject_id
 * @property string $question_type
 * @property string $instruction
 * @property string $duration
 * @property string $mark
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereInstruction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CbtSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'subject_id',
    'question_type',
    'instruction',
    'duration',
    'mark'
])]
class CbtSetting extends Model
{
    use HasFactory;
}


