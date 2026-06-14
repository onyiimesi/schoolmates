<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $staff_id
 * @property string $time_in
 * @property string $date_in
 * @property string|null $time_out
 * @property string|null $date_out
 * @property string|null $ip_address
 * @property string|null $device
 * @property string|null $os
 * @property string|null $address
 * @property array<array-key, mixed>|null $location
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff $staff
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereDateIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereDateOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereIpAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereOs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereTimeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffScanAttendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'staff_id',
    'time_in',
    'date_in',
    'time_out',
    'date_out',
    'ip_address',
    'device',
    'os',
    'location',
    'status',
    'address',
])]
class StaffScanAttendance extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'location' => 'array'
        ];
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
