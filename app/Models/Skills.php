<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $skill_type
 * @property array<array-key, mixed> $attribute
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereAttribute($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereSkillType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Skills whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'skill_type',
    'attribute'
])]
class Skills extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
    protected function casts(): array
    {
        return [
            'attribute' => 'array',
        ];
    }
}
