<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $name
 * @property string $unique_id
 * @property numeric|null $cost_price
 * @property numeric|null $selling_price
 * @property int|null $quantity
 * @property string|null $image
 * @property string|null $image_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schools|null $school
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StationaryPurchase> $stationaryPurchases
 * @property-read int|null $stationary_purchases_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StationarySale> $stationarySales
 * @property-read int|null $stationary_sales_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereCostPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereImageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereSellingPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereUniqueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Stationary whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'name',
    'unique_id',
    'cost_price',
    'selling_price',
    'quantity',
    'image',
    'image_id',
])]
class Stationary extends Model
{
    protected function casts(): array
    {
        return [
            'cost_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
        ];
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function stationarySales()
    {
        return $this->hasMany(StationarySale::class, 'stationary_id');
    }

    public function stationaryPurchases()
    {
        return $this->hasMany(StationaryPurchase::class, 'stationary_id');
    }
}
