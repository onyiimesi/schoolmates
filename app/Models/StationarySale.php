<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $stationary_id
 * @property string $sch_id
 * @property string $campus
 * @property int $class_id
 * @property int $student_id
 * @property string|null $date
 * @property int|null $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ClassModel|null $class
 * @property-read \App\Models\Schools|null $school
 * @property-read \App\Models\Stationary $stationary
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereStationaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StationarySale whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'stationary_id',
    'sch_id',
    'campus',
    'class_id',
    'student_id',
    'date',
    'quantity',
])]
class StationarySale extends Model
{
    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function stationary()
    {
        return $this->belongsTo(Stationary::class, 'stationary_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
