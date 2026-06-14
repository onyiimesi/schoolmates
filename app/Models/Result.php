<?php

namespace App\Models;

use App\Enum\ResultStatus;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string|null $campus_type
 * @property string $student_id
 * @property string $student_fullname
 * @property string $admission_number
 * @property string $class_name
 * @property string $period
 * @property string $term
 * @property string $session
 * @property string|null $school_opened
 * @property string|null $times_present
 * @property string|null $times_absent
 * @property string|null $teacher_comment
 * @property string|null $teacher_id
 * @property string|null $teacher_fullname
 * @property string|null $hos_comment
 * @property string|null $hos_id
 * @property string|null $hos_fullname
 * @property string|null $total
 * @property string|null $performance_remark
 * @property string|null $computed_midterm
 * @property string|null $computed_endterm
 * @property string|null $result_type Type of result, e.g., midterm, end term
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Abacus|null $abacus
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AffectiveDisposition> $affectiveDispositions
 * @property-read int|null $affective_dispositions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PsychomotorPerformance> $psychomotorPerformances
 * @property-read int|null $psychomotor_performances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PsychomotorSkill> $psychomotorskill
 * @property-read int|null $psychomotorskill_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PupilReport> $pupilReports
 * @property-read int|null $pupil_reports_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ResultExtraCurricular> $resultExtraCurriculars
 * @property-read int|null $result_extra_curriculars_count
 * @property-read \App\Models\Staff|null $results
 * @property-read \App\Models\Student|null $student
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StudentScore> $studentScores
 * @property-read int|null $student_scores_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereCampusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereComputedEndterm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereComputedMidterm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereHosComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereHosFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereHosId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result wherePerformanceRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereResultType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereSchoolOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereStudentFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTeacherComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTeacherFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTimesAbsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTimesPresent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Result whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'campus_type',
    'student_id',
    'student_fullname',
    'admission_number',
    'class_name',
    'period',
    'term',
    'session',
    'total',
    'grade',
    'remark',
    'total_subject',
    'total_student',
    'student_average',
    'class_average',
    'percent_score',
    'results',
    'school_opened',
    'times_present',
    'times_absent',
    'affective_disposition',
    'psychomotor_skills',
    'teacher_comment',
    'teacher_id',
    'teacher_fullname',
    'hos_comment',
    'hos_id',
    'hos_fullname',
    'computed_midterm',
    'computed_endterm',
    'status',
    'computed_midterm',
    'computed_endterm',
    'result_type',
    'performance_remark'
])]
class Result extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected function casts(): array
    {
        return [
            'results' => 'array',
            'affective_disposition' => 'array',
            'psychomotor_skills' => 'array',
        ];
    }

    public static function createOne(Staff $staff, mixed $data, mixed $hos): static
    {
        $create = new self();

        $create->sch_id = $staff->sch_id;
        $create->campus = $staff->campus;
        $create->campus_type = $staff->campus_type;
        $create->student_id = $data->student_id;
        $create->student_fullname = $data->student_fullname;
        $create->admission_number = $data->admission_number;
        $create->class_name = $data->class_name;
        $create->period = $data->period;
        $create->term = $data->term;
        $create->session = $data->session;
        $create->school_opened = $data->school_opened;
        $create->times_present = $data->times_present;
        $create->times_absent = $data->times_absent;
        $create->performance_remark = $data->performance_remark;
        $create->teacher_comment = $data->teacher_comment;
        $create->teacher_id = $data->teacher_id;
        $create->teacher_fullname = $staff->surname . ' '. $staff->firstname;
        $create->hos_comment = $data->hos_comment;
        $create->hos_id = $data->hos_id;
        $create->hos_fullname = filled($hos) ? "{$hos->surname} {$hos->firstname}" : null;
        $create->computed_endterm = 'true';
        $create->result_type = 'endterm';
        $create->status = ResultStatus::NOTRELEASED->value;
        $create->save();

        return $create;
    }

    public function results(): BelongsTo
    {
        return $this->belongsTo(Staff::class);
    }

    public function studentScores(): HasMany
    {
        return $this->hasMany(StudentScore::class, 'result_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function affectiveDispositions(): HasMany
    {
        return $this->hasMany(AffectiveDisposition::class, 'result_id');
    }

    public function psychomotorskill(): HasMany
    {
        return $this->hasMany(PsychomotorSkill::class, 'result_id');
    }

    public function resultExtraCurriculars(): HasMany
    {
        return $this->hasMany(ResultExtraCurricular::class, 'result_id');
    }

    public function abacus(): HasOne
    {
        return $this->hasOne(Abacus::class, 'result_id');
    }

    public function psychomotorPerformances(): HasMany
    {
        return $this->hasMany(PsychomotorPerformance::class, 'result_id');
    }

    public function pupilReports(): HasMany
    {
        return $this->hasMany(PupilReport::class, 'result_id');
    }
}
