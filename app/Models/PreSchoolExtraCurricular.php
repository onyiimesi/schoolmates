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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolExtraCurricular whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'sch_id',
    'campus'
])]
class PreSchoolExtraCurricular extends Model
{
    use HasFactory;
}
