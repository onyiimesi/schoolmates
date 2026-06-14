<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $school_name
 * @property string $school_slug
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string $email
 * @property string|null $phone_number
 * @property string $amount
 * @property string $reference
 * @property string $channel
 * @property string $currency
 * @property string $ip_address
 * @property string $pricing_id
 * @property string $payment_portal_url
 * @property string $paid_at
 * @property string $createdAt
 * @property string $transaction_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schools|null $school
 * @property-read \App\Models\Staff|null $staff
 * @property-read \App\Models\Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereChannel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment wherePaidAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment wherePaymentPortalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment wherePricingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereSchoolName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereSchoolSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereTransactionDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolPayment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Table(name: 'school_payments')]
class SchoolPayment extends Model
{
    use HasFactory;

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'sch_id', 'sch_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'sch_id', 'sch_id');
    }
}
