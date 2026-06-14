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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PupilReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'score'
])]
class PupilReport extends Model
{
    use HasFactory;
}
