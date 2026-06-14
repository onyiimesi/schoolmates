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
 * @property string $student_fullname
 * @property string $class
 * @property string $sub_class
 * @property string $subject
 * @property string $period
 * @property string $term
 * @property string $session
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereStudentFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereSubClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RegisterSubject whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id', 
    'campus',
    'admission_number', 
    'student_fullname', 
    'class', 
    'sub_class', 
    'subject', 
    'period', 
    'term', 
    'session', 
])]
class RegisterSubject extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
