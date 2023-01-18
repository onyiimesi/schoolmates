<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'term', 
        'session', 
        'bank_name', 
        'account_name', 
        'student_fullname',
        'payment_method',
        'amount_paid',
        'total_amount',
        'amount_due',
        'remark',
        'status'

    ];
}
