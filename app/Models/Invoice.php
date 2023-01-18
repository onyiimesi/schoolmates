<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id', 
        'campus', 
        'admission_number',
        'fullname', 
        'class',
        'feetype', 
        'amount',
        'notation',
        'discount',
        'discount_amount',
        'term',
        'session',
        'invoice_no',
    ];
}
