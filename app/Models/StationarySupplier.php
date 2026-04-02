<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationarySupplier extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'first_name',
        'last_name',
        'phone_number',
        'address',
        'amount_owed',
        'amount_paid',
    ];

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
