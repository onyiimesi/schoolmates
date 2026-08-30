<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeatureDenialLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'feature_key',
        'reason',
        'email_sent',
        'notified_at',
    ];

    protected $casts = [
        'email_sent' => 'boolean',
        'notified_at' => 'datetime',
    ];
}
