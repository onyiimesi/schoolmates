<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VehicleLog extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'vehicle_number', 
        'driver_name', 
        'route', 
        'purpose', 
        'mechanic_condition', 
        'add_info', 
        'date_out', 
        'time_out'
    ];
}
