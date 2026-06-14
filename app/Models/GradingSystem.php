<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string $score_from
 * @property string $score_to
 * @property string $grade
 * @property string $remark
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereScoreFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereScoreTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GradingSystem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'id',
    'score_from', 
    'score_to', 
    'grade', 
    'remark',
    'created_by',
    'sch_id',
    'campus'
])]
class GradingSystem extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
