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
 * @property string $subject
 * @property array<array-key, mixed> $topic
 * @property string|null $category
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'subject',
    'topic',
    'category'
])]
class PreSchoolSubject extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected function casts(): array
    {
        return [
            'topic' => 'array',
        ];
    }
}
