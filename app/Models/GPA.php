<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $min_mark Minimum mark range
 * @property string $max_mark Maximum mark range
 * @property string $remark Grade remark
 * @property numeric $grade_point Grade point value
 * @property string|null $key_range Grade key range
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schools|null $school
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereGradePoint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereKeyRange($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereMaxMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereMinMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GPA whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'min_mark',
    'max_mark',
    'remark',
    'grade_point',
    'key_range'
])]
class GPA extends Model
{
    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }
}
