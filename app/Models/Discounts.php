<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Discounts whereValue($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'value'
])]
class Discounts extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
