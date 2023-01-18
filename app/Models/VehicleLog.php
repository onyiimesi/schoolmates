<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleLog extends Model
{
    use HasFactory;

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
