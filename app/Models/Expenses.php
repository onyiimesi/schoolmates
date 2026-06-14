<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property string $expense_category
 * @property string $bank_name
 * @property string $account_name
 * @property string $payment_type
 * @property string $beneficiary
 * @property string|null $transaction_id
 * @property string $amount
 * @property string $purpose
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereBeneficiary($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereExpenseCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Expenses whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'term',
    'session', 
    'expense_category',        
    'bank_name', 
    'account_name', 
    'payment_type', 
    'beneficiary', 
    'transaction_id', 
    'amount', 
    'purpose', 
])]
class Expenses extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
