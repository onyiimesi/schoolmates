<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'report_type',
        'attribute'
    ];

    protected $casts = [
        'attribute' => 'array',
    ];
}
