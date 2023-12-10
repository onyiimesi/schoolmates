<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultExtraCurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'result_id',
        'name',
        'value'
    ];
}
