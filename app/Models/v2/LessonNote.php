<?php

namespace App\Models\v2;

use App\Models\ClassModel;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string|null $term
 * @property string|null $session
 * @property int $staff_id
 * @property string $week
 * @property int $subject_id
 * @property int $class_id
 * @property string $topic
 * @property string|null $sub_topic
 * @property string|null $specific_objectives
 * @property string|null $previous_lesson
 * @property string|null $previous_knowledge
 * @property string|null $set_induction
 * @property string|null $methodology
 * @property string|null $teaching_aid
 * @property string $description
 * @property string $file
 * @property string|null $file_name
 * @property string|null $file_id
 * @property string $submitted_by
 * @property string $status
 * @property string|null $date_submitted
 * @property string|null $date_approved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $date_from
 * @property \Illuminate\Support\Carbon|null $date_to
 * @property-read ClassModel|null $class
 * @property-read Staff|null $staff
 * @property-read Subject|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereDateApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereDateFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereDateSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereDateTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereMethodology($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote wherePreviousKnowledge($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote wherePreviousLesson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSetInduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSpecificObjectives($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSubTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereTeachingAid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LessonNote whereWeek($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'term',
    'session',
    'staff_id',
    'week',
    'subject_id',
    'class_id',
    'topic',
    'description',
    'file',
    'file_name',
    'file_id',
    'submitted_by',
    'status',
    'date_submitted',
    'date_approved',
    'date_from',
    'date_to',
    'sub_topic',
    'specific_objectives',
    'previous_lesson',
    'previous_knowledge',
    'set_induction',
    'methodology',
    'teaching_aid',
])]
class LessonNote extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to' => 'date',
        ];
    }
}
