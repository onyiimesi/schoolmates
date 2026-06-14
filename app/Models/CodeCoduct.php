<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $rule
 * @property string $description
 * @property string $apply_to
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereApplyTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereRule($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CodeCoduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'rule', 
    'description', 
    'apply_to', 
    'sch_id',
    'campus'
])]
class CodeCoduct extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
