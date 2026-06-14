<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property array<array-key, mixed> $data
 * @property string|null $class
 * @property string|null $period
 * @property string|null $term
 * @property string|null $session
 * @property string $attendance_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereAttendanceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentAttendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus', 
    'student_id',
    'admission_number', 
    'student_fullname', 
    'class', 
    'period',
    'term',
    'session',
    'attendance_date',
    'status',
    'data'
])]
class StudentAttendance extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }
    

}
