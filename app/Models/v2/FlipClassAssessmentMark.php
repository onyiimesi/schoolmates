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
 * @property string $topic
 * @property string $question
 * @property string $question_type
 * @property string $question_number
 * @property string $answer
 * @property string $correct_answer
 * @property string $mark
 * @property string $teacher_mark
 * @property string $submitted
 * @property string $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\FlipClassAssessment $flipClassAssessment
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereFlipClassAssessmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereTeacherMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentMark whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'question_type',
    'question',
    'answer',
    'subject_id',
    'student_id',
    'correct_answer',
    'mark',
    'sch_id',
    'campus',
    'session',
    'period',
    'term',
    'flip_class_assessment_id',
    'submitted',
    'question_number',
    'teacher_mark',
    'week',
    'topic',
])]
class FlipClassAssessmentMark extends Model
{
    use HasFactory;

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
