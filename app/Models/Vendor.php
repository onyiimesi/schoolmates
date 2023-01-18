<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_code',
        'vendor_type', 
        'vendor_name',        
        'company_name', 
        'contact_address', 
        'contact_person', 
        'contact_phone', 
        'email_address',
    ];
}
