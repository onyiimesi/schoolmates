<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $flip_class_assessment_id
 * @property string $student_id
 * @property string $subject_id
 * @property string $question_type
 * @property string $student_mark
 * @property string $total_mark
 * @property string $score
 * @property string $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereFlipClassAssessmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereStudentMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentResult whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'student_id',
    'subject_id',
    'question_type',
    'student_mark',
    'total_mark',
    'score',
    'flip_class_assessment_id',
    'week'
])]
class FlipClassAssessmentResult extends Model
{
    use HasFactory;
}
