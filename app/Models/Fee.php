<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $pid
 * @property string|null $campus
 * @property string $feetype
 * @property string $amount
 * @property string $term
 * @property string|null $session
 * @property string|null $fee_status
 * @property string|null $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereFeeStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereFeetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee wherePid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Fee whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'pid',
    'campus',
    'feetype',
    'amount',
    'term',
    'session',
    'fee_status',
    'category',
])]
class Fee extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
