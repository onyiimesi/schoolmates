<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $day
 * @property string $wear
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DressCode whereWear($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'day', 
    'wear', 
    'description', 
    'sch_id',
    'campus'
])]
class DressCode extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
