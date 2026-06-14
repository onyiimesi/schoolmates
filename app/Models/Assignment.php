<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string|null $period
 * @property string|null $term
 * @property string $session
 * @property string $teacher_id
 * @property string $question_type
 * @property string $question
 * @property string|null $question_number
 * @property string $answer
 * @property int|null $subject_id
 * @property int|null $subject_class_id
 * @property string|null $option1
 * @property string|null $option2
 * @property string|null $option3
 * @property string|null $option4
 * @property string|null $image
 * @property string|null $total_question
 * @property string|null $question_mark
 * @property string|null $total_mark
 * @property string|null $week
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Staff|null $staff
 * @property-read \App\Models\Subject|null $subject
 * @property-read \App\Models\SubjectClass|null $subjectClass
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereOption1($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereOption2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereOption3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereOption4($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereQuestionMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereQuestionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereQuestionType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereSubjectClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTotalMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereTotalQuestion($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Assignment whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'question_type',
    'question',
    'answer',
    'subject_id',
    'subject_class_id',
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
class Assignment extends Model
{
    use HasFactory;

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function subjectClass()
    {
        return $this->belongsTo(SubjectClass::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'teacher_id');
    }
}
