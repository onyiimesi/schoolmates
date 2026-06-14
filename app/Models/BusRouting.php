<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property string $admission_number
 * @property string $student_id
 * @property string $bus_type
 * @property string $bus_number
 * @property string $driver_name
 * @property string $driver_phonenumber
 * @property string $driver_image
 * @property string $conductor_name
 * @property string $conductor_phonenumber
 * @property string $conductor_image
 * @property string $route
 * @property string $ways
 * @property string $pickup_time
 * @property string $dropoff_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereBusNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereBusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereConductorImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereConductorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereConductorPhonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereDriverImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereDriverName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereDriverPhonenumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereDropoffTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting wherePickupTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereRoute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|BusRouting whereWays($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
])]
class BusRouting extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
