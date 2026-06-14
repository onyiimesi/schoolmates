<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string $admission_number
 * @property string $student_id
 * @property string $fullname
 * @property string $class
 * @property array<array-key, mixed> $feetype
 * @property string|null $amount
 * @property string|null $notation
 * @property string|null $discount
 * @property string|null $discount_amount
 * @property string $term
 * @property string $session
 * @property string $invoice_no
 * @property string|null $due_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payment
 * @property-read int|null $payment_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDiscountAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereDueDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereFeetype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereInvoiceNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereNotation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Invoice whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'admission_number',
    'student_id',
    'fullname',
    'class',
    'feetype',
    'amount',
    'notation',
    'discount',
    'discount_amount',
    'term',
    'session',
    'invoice_no',
    'due_date'
])]
class Invoice extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function payment()
    {
        return $this->hasMany(Payment::class, 'invoice_id');
    }
    protected function casts(): array
    {
        return [
            'feetype' => 'array'
        ];
    }
}
