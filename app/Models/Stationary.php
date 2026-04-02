<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stationary extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'name',
        'unique_id',
        'cost_price',
        'selling_price',
        'quantity',
        'image',
        'image_id',
    ];

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
