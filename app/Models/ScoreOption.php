<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $label
 * @property string $segments
 * @property int $is_default
 * @property string|null $assessment_type Type of assessment, e.g., 1 or 2 e.t.c
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereAssessmentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereIsDefault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereSegments($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScoreOption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Table(name: 'score_options')]
class ScoreOption extends Model
{
}
