<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class BusRouting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'admission_number',
        'student_id',
        'bus_type',
        'bus_number',
        'driver_name',
        'driver_phonenumber',
        'driver_image',
        'conductor_name',
        'conductor_phonenumber',
        'conductor_image',
        'route',
        'ways',
        'pickup_time',
        'dropoff_time'
    ];
}
