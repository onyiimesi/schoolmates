<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $table = 'subscriptions';

    protected $fillable = [
        'school_id',
        'term',
        'session',
        'starts_at',
        'ends_at',
        'amount',
        'status',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'amount' => 'decimal:2',
    ];


    public function school()
    {
        return $this->belongsTo(Schools::class, 'school_id', 'id');
    }
}
