<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property string $title
 * @property string $description
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Timetable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'title', 
    'description', 
    'file', 
    'sch_id', 
    'period', 
    'term',
    'session',
    'campus', 
])]
class Timetable extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
