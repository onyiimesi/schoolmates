<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone_number
 * @property string|null $address
 * @property numeric|null $amount_owed
 * @property numeric|null $amount_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schools|null $school
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereAmountOwed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereAmountPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySupplier whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'first_name',
    'last_name',
    'phone_number',
    'address',
    'amount_owed',
    'amount_paid',
])]
class StationarySupplier extends Model
{
    protected function casts(): array
    {
        return [
            'amount_owed' => 'decimal:2',
            'amount_paid' => 'decimal:2',
        ];
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }
}
