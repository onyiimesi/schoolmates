<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $admission_number
 * @property string $student_id
 * @property string $student_fullname
 * @property string $date_of_incident
 * @property string $time_of_incident
 * @property string $condition
 * @property string $state
 * @property string $report_details
 * @property string $action_taken
 * @property string $recommendation
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereActionTaken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereCondition($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereDateOfIncident($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereRecommendation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereReportDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereStudentFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereTimeOfIncident($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|HealthReport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'admission_number',
    'student_id',
    'student_fullname',
    'date_of_incident',
    'time_of_incident',
    'condition',
    'state',
    'report_details',
    'action_taken',
    'recommendation'

])]
class HealthReport extends Model implements Auditable
{ 
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
