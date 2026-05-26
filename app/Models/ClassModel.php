<?php

namespace App\Models;

use App\Enum\StaffStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use OwenIt\Auditing\Contracts\Auditable;

class ClassModel extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'id',
        'sch_id',
        'campus',
        'campus_type',
        'class_name',
        'sub_class',
    ];

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
