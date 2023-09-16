<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Expenses extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
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
