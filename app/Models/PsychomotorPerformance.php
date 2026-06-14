<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $name
 * @property string|null $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorPerformance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'score'
])]
class PsychomotorPerformance extends Model
{
    use HasFactory;
}
