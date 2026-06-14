<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $bank_name
 * @property string $account_name
 * @property string $opening_balance
 * @property string|null $account_number
 * @property string|null $account_purpose
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereAccountPurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereOpeningBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Bank whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'bank_name',
    'account_name',
    'opening_balance',
    'account_number',
    'account_purpose'
])]
class Bank extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bank_id');
    }
}
