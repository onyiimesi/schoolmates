<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationaryPurchase extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'stationary_supplier_id',
        'date_supplied',
        'stationary_id',
        'quantity',
        'price',
    ];

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
