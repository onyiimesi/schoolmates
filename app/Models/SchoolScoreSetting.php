<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property int $score_option_id
 * @property string|null $value_score
 * @property int|null $previous_score_option_id
 * @property string|null $previous_value_score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ScoreOption|null $scoreOption
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting byCampus($user)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting wherePreviousScoreOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting wherePreviousValueScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereScoreOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolScoreSetting whereValueScore($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'score_option_id',
    'value_score',
    'previous_score_option_id',
    'previous_value_score',
])]
class SchoolScoreSetting extends Model
{
    public function scoreOption()
    {
        return $this->belongsTo(ScoreOption::class);
    }

    protected function scopeByCampus($query, $user)
    {
        return $query->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus);
    }
}
