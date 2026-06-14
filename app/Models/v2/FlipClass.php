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
 * @property string $term
 * @property string $session
 * @property int $staff_id
 * @property string $week
 * @property int $subject_id
 * @property int $class_id
 * @property string $topic
 * @property string|null $description
 * @property string|null $video_url
 * @property string $file
 * @property string|null $file_id
 * @property string|null $file_name
 * @property string|null $date_submitted
 * @property string|null $submitted_by
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\FlipClassAssessment> $assessments
 * @property-read int|null $assessments_count
 * @property-read ClassModel|null $class
 * @property-read Staff|null $staff
 * @property-read Subject|null $subject
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereDateSubmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereSubmittedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereTopic($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereVideoUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|FlipClass whereWeek($value)
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
    'video_url',
    'file',
    'file_name',
    'file_id',
    'submitted_by',
    'status',
    'date_submitted',
])]
class FlipClass extends Model
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

    public function assessments()
    {
        return $this->hasMany(FlipClassAssessment::class, 'flip_class_id');
    }
}
