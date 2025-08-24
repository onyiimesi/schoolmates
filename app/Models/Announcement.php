<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $fillable = [
        'priority',
        'title',
        'description',
        'schools',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'schools' => 'array',
        'expiry_date' => 'date',
    ];

    protected $hidden = [
        'schools',
        'expiry_date',
        'created_at',
        'updated_at',
    ];
}
