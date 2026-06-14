<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $from
 * @property string $to
 * @property string $amount
 * @property string $memo
 * @property string $transfer_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereMemo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereTransferDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TransferFunds whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus', 
    'from',
    'to',
    'amount',
    'memo',
    'transfer_date',
])]
class TransferFunds extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
