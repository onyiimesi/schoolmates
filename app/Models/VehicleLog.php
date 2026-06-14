<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $vehicle_number
 * @property string $driver_name
 * @property string $route
 * @property string $purpose
 * @property string $mechanic_condition
 * @property string $add_info
 * @property string $date_out
 * @property string $time_out
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereAddInfo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereDateOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereMechanicCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog wherePurpose($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleLog whereVehicleNumber($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'vehicle_number', 
    'driver_name', 
    'route', 
    'purpose', 
    'mechanic_condition', 
    'add_info', 
    'date_out', 
    'time_out',
    'sch_id',
    'campus', 
])]
class VehicleLog extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
