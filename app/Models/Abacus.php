<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Abacus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'result_id',
    'name'
])]
class Abacus extends Model
{
    use HasFactory;
}
