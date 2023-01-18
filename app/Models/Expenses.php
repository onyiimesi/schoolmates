<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    use HasFactory;

    protected $fillable = [
        'term',
        'session', 
        'expense_category',        
        'bank_name', 
        'account_name', 
        'payment_type', 
        'beneficiary', 
        'transaction_id', 
        'amount', 
        'purpose', 
    ];
}
