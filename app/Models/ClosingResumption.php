<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property string $session_ends
 * @property string $session_resumes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereSessionEnds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereSessionResumes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClosingResumption whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'term',
    'campus',
    'session',
    'session_ends',
    'session_resumes'
])]
class ClosingResumption extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
