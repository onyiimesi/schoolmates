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
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicCalender whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'title', 
    'description', 
    'file', 
    'sch_id', 
    'campus', 
    'period', 
    'term',
    'session',
])]
class AcademicCalender extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
