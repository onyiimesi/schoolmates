<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $offence_type
 * @property string $offence_action
 * @property string $fine
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereFine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereOffenceAction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereOffenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DisciplinaryAction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'offence_type', 
    'offence_action',
    'fine',
    'sch_id',
    'campus'
])]
class DisciplinaryAction extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
