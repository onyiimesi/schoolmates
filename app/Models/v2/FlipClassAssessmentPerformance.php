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
 * @property string $total_mark
 * @property string $percentage_score
 * @property string $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\FlipClassAssessment $flipClassAssessment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereFlipClassAssessmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance wherePercentageScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentPerformance whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'session',
    'flip_class_assessment_id',
    'student_id',
    'subject_id',
    'question_type',
    'total_mark',
    'percentage_score',
    'week'
])]
class FlipClassAssessmentPerformance extends Model
{
    use HasFactory;

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
