<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchool whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'name'
])]
class PreSchool extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
