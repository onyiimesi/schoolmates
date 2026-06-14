<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $staff_id
 * @property string $vehicle_type
 * @property string $vehicle_make
 * @property string $vehicle_number
 * @property string $driver_name
 * @property string $detected_fault
 * @property string $mechanic_name
 * @property string $mechanic_phone
 * @property string $cost_of_maintenance
 * @property string $initial_payment
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereCostOfMaintenance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereDetectedFault($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereInitialPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereMechanicName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereMechanicPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereVehicleMake($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereVehicleNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VehicleMaintenance whereVehicleType($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
])]
class VehicleMaintenance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
