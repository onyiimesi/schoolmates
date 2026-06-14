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
 * @property bool $is_current_period
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereIsCurrentPeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AcademicPeriod whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'is_current_period',
])]
class AcademicPeriod extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected function casts(): array
    {
        return [
            'is_current_period' => 'boolean'
        ];
    }
}
