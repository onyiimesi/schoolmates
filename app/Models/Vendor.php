<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Vendor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'vendor_code',
        'vendor_type', 
        'initial_balance',
        'vendor_name',        
        'company_name', 
        'contact_address', 
        'contact_person', 
        'contact_phone', 
        'email_address',
    ];
}
