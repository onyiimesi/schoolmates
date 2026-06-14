<?php

namespace App\Models;

use App\Enum\StaffStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string|null $sch_id
 * @property string|null $campus
 * @property string|null $campus_type
 * @property string $class_name
 * @property string|null $sub_class
 * @property string|null $class_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectTeacher> $subjectTeachers
 * @property-read int|null $subject_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectClass> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereCampusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereClassType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereSubClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ClassModel whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'id',
    'sch_id',
    'campus',
    'campus_type',
    'class_name',
    'sub_class',
])]
class ClassModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected function className(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    public function subjects()
    {
        return $this->hasMany(SubjectClass::class, 'class_id');
    }

    public function subjectTeachers(): HasMany
    {
        return $this->hasMany(SubjectTeacher::class, 'class_id');
    }

    /**
     * Class teacher is matched by class_name since class_assigned stores the name string.
     */
    public function classTeacher(): HasOne
    {
        $user = userAuth();

        return $this->hasOne(Staff::class, 'class_assigned', 'class_name')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('teacher_type', 'class teacher')
            ->where('status', StaffStatus::ACTIVE);
    }
}
