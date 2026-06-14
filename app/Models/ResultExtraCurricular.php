<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ResultExtraCurricular whereValue($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'result_id',
    'name',
    'value'
])]
class ResultExtraCurricular extends Model
{
    use HasFactory;
}
