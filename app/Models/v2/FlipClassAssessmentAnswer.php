<?php

namespace App\Models\v2;

use App\Models\Student;
use App\Models\Subject;
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
 * @property string|null $topic
 * @property string $question
 * @property string $question_number
 * @property string $question_type
 * @property string $answer
 * @property string $correct_answer
 * @property string $mark
 * @property string $submitted
 * @property string $week
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\FlipClassAssessment $flipClassAssessment
 * @property-read Student|null $student
 * @property-read Subject|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereCorrectAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereFlipClassAssessmentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessmentAnswer whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'question_type', 'question', 'answer', 'subject_id', 'student_id', 'correct_answer', 'mark', 'sch_id', 'campus', 'session', 'period', 'term', 'flip_class_assessment_id', 'submitted', 'question_number', 'week', 'topic'
])]
class FlipClassAssessmentAnswer extends Model
{
    use HasFactory;

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function flipClassAssessment()
    {
        return $this->belongsTo(FlipClassAssessment::class, 'flip_class_assessment_id');
    }
}
