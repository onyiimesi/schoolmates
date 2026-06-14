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
 * @property int $invoice_id
 * @property int $bank_id
 * @property string $bank_name
 * @property string $account_name
 * @property string $student_id
 * @property string $student_fullname
 * @property string $payment_method
 * @property string $amount_paid
 * @property string $total_amount
 * @property string $amount_due
 * @property string $type
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Bank|null $bank
 * @property-read \App\Models\Invoice|null $invoice
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAccountName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmountDue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereInvoiceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStudentFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereTotalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Payment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'term',
    'session',
    'bank_id',
    'bank_name',
    'account_name',
    'student_id',
    'invoice_id',
    'student_fullname',
    'payment_method',
    'amount_paid',
    'total_amount',
    'amount_due',
    'type',
    'status'
])]
class Payment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
