<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdmissionNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_number'
    ];
}
