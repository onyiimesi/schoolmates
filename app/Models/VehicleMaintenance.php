<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class VehicleMaintenance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'staff_id',
        'vehicle_type',
        'vehicle_make',
        'vehicle_number',
        'driver_name',
        'detected_fault',
        'mechanic_name',
        'mechanic_phone',
        'cost_of_maintenance',
        'initial_payment'
    ];
}
