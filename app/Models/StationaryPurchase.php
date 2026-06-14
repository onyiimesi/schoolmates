<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property int $stationary_supplier_id
 * @property string $date_supplied
 * @property int $stationary_id
 * @property int $quantity
 * @property numeric $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schools|null $school
 * @property-read \App\Models\Stationary $stationary
 * @property-read \App\Models\StationarySupplier $stationarySupplier
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereDateSupplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereStationaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereStationarySupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationaryPurchase whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'stationary_supplier_id',
    'date_supplied',
    'stationary_id',
    'quantity',
    'price',
])]
class StationaryPurchase extends Model
{
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function stationarySupplier()
    {
        return $this->belongsTo(StationarySupplier::class, 'stationary_supplier_id');
    }

    public function stationary()
    {
        return $this->belongsTo(Stationary::class, 'stationary_id');
    }
}
