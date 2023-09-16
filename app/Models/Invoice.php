<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Invoice extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id', 
        'campus', 
        'admission_number',
        'student_id',
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
