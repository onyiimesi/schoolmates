<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $name
 * @property string $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PsychomotorSkill whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'score'
])]
class PsychomotorSkill extends Model
{
    use HasFactory;
}
