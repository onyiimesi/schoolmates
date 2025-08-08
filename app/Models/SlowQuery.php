<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SlowQuery extends Model
{
    protected $fillable = [
        'fingerprint','connection','sql','raw_sql','bindings','time',
        'occurrences','max_time','avg_time','file','line',
        'first_seen_at','last_seen_at','resolved',
    ];

    protected $casts = [
        'bindings' => 'array',
        'first_seen_at' => 'datetime',
        'last_seen_at'  => 'datetime',
        'resolved'      => 'boolean',
    ];
}
