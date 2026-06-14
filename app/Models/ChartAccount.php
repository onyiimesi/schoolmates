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
 * @property string $acct_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereAcctType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ChartAccount whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'name',
    'acct_type',
])]
class ChartAccount extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
