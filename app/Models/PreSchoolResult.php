<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $student_id
 * @property string $student_fullname
 * @property string $admission_number
 * @property string $class_name
 * @property string $period
 * @property string $term
 * @property string $session
 * @property array<array-key, mixed> $evaluation_report
 * @property array<array-key, mixed> $cognitive_development
 * @property string|null $school_opened
 * @property string|null $times_present
 * @property string|null $times_absent
 * @property string $teacher_comment
 * @property string $teacher_id
 * @property string|null $teacher_fullname
 * @property string|null $teacher_signature
 * @property string|null $hos_comment
 * @property string|null $hos_id
 * @property string|null $hos_fullname
 * @property string|null $hos_signature
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PreSchoolResultExtraCurricular> $preschoolresultextracurricular
 * @property-read int|null $preschoolresultextracurricular_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereCognitiveDevelopment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereEvaluationReport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereHosComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereHosFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereHosId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereHosSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereSchoolOpened($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereStudentFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTeacherComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTeacherFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTeacherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTeacherSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTimesAbsent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereTimesPresent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PreSchoolResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'student_id',
    'student_fullname',
    'admission_number',
    'class_name',
    'period',
    'term',
    'session',
    'evaluation_report',
    'cognitive_development',
    'school_opened',
    'times_present',
    'times_absent',
    'teacher_comment',
    'teacher_id',
    'hos_comment',
    'hos_id',
    'hos_fullname',
    'hos_signature',
    'status'
])]
class PreSchoolResult extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected function casts(): array
    {
        return [
            'evaluation_report' => 'array',
            'cognitive_development' => 'array'
        ];
    }

    public function preschoolresultextracurricular(): HasMany
    {
        return $this->hasMany(PreSchoolResultExtraCurricular::class, 'pre_school_result_id', 'id');
    }
}
