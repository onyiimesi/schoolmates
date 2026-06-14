<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property string $class_id
 * @property string|null $class
 * @property string|null $category
 * @property array<array-key, mixed> $subjects
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereCategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereSubjects($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass withTrashed(bool $withTrashed = true)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolSubjectClass withoutTrashed()
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'class_id',
    'class',
    'category',
    'subjects'
])]
class PreSchoolSubjectClass extends Model
{
    use HasFactory;

    use SoftDeletes;
    protected function casts(): array
    {
        return [
            'subjects' => 'array',
        ];
    }
}
