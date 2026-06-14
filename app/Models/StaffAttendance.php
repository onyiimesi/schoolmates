<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string $staff_id
 * @property string|null $time_in
 * @property string|null $date_in
 * @property string|null $time_out
 * @property string|null $date_out
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereDateIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereDateOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereTimeIn($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereTimeOut($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StaffAttendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'staff_id', 
    'time_in', 
    'date_in', 
    'time_out', 
    'date_out',

])]
class StaffAttendance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
