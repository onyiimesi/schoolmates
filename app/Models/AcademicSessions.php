<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $academic_session
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereAcademicSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicSessions whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id', 
    'campus', 
    'academic_session'
])]
class AcademicSessions extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
