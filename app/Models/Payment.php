<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Payment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'bank_id',
        'bank_name',
        'account_name',
        'student_id',
        'invoice_id',
        'student_fullname',
        'payment_method',
        'amount_paid',
        'total_amount',
        'amount_due',
        'type',
        'status'
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }
}
