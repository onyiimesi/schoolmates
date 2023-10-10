<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Result extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
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
        'computed_endterm'
    ];

    protected $casts = [
        'results' => 'array',
        'affective_disposition' => 'array',
        'psychomotor_skills' => 'array',
    ];

    public function results()
    {
        return $this->belongsTo(Staff::class);
    }

    public function studentscore()
    {
        return $this->hasMany(StudentScore::class, 'result_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function affectivedisposition()
    {
        return $this->hasMany(AffectiveDisposition::class);
    }

    public function psychomotorskill()
    {
        return $this->hasMany(PsychomotorSkill::class);
    }
}
