<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StationarySale extends Model
{
    protected $fillable = [
        'stationary_id',
        'sch_id',
        'campus',
        'class_id',
        'student_id',
        'date',
        'quantity',
    ];

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
