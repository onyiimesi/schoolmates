<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ExtraCurricular whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'sch_id',
    'campus'
])]
class ExtraCurricular extends Model
{
    use HasFactory;
}
