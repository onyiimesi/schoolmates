<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $pre_school_result_id
 * @property string $name
 * @property string $value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular wherePreSchoolResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResultExtraCurricular whereValue($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'pre_school_result_id',
    'name',
    'value'
])]
class PreSchoolResultExtraCurricular extends Model
{
    use HasFactory;
}
