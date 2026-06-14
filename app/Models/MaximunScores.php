<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string $midterm
 * @property string|null $first_assessment
 * @property string|null $second_assessment
 * @property int|null $has_two_assessment
 * @property string $exam
 * @property string $total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereExam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereFirstAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereHasTwoAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereMidterm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereSecondAssessment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MaximunScores whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'midterm', 
    'exam', 
    'total',
    'sch_id',
    'campus',
    'first_assessment',
    'second_assessment',
    'has_two_assessment'
])]
class MaximunScores extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
