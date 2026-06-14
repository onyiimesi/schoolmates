<?php

namespace App\Models\v2;

use App\Models\Staff;
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
 * @property string $teacher_id
 * @property int $flip_class_id
 * @property string $question_type
 * @property string|null $topic
 * @property string $question
 * @property string $question_number
 * @property string $answer
 * @property int $subject_id
 * @property string|null $option1
 * @property string|null $option2
 * @property string|null $option3
 * @property string|null $option4
 * @property string|null $image
 * @property string|null $total_question
 * @property string|null $question_mark
 * @property string|null $total_mark
 * @property string|null $week
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\FlipClass $flipClass
 * @property-read Staff|null $staff
 * @property-read Subject $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereFlipClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereOption1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereOption2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereOption3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereOption4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereQuestionMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereTotalQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClassAssessment whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'flip_class_id',
    'topic',
    'question_type',
    'question',
    'answer',
    'subject_id',
    'option1',
    'option2',
    'option3',
    'option4',
    'image',
    'sch_id',
    'campus',
    'session',
    'teacher_id',
    'period',
    'term',
    'total_question',
    'question_mark',
    'total_mark',
    'question_number',
    'week',
    'status'
])]
class FlipClassAssessment extends Model
{
    use HasFactory;

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }

    public function flipClass()
    {
        return $this->belongsTo(FlipClass::class);
    }
}
