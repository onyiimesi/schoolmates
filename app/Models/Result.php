<?php

namespace App\Models;

use App\Enum\ResultStatus;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'computed_endterm',
        'result_type',
        'performance_remark'
    ];

    protected $casts = [
        'results' => 'array',
        'affective_disposition' => 'array',
        'psychomotor_skills' => 'array',
    ];

    public static function createOne($staff, $data, $hos)
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
        $create->hos_fullname = !empty($hos) ? "{$hos->surname} {$hos->firstname}" : null;
        $create->computed_endterm = 'true';
        $create->result_type = 'endterm';
        $create->status = ResultStatus::NOTRELEASED->value;
        $create->save();

        return $create;
    }

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
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function affectivedisposition()
    {
        return $this->hasMany(AffectiveDisposition::class);
    }

    public function psychomotorskill()
    {
        return $this->hasMany(PsychomotorSkill::class);
    }

    public function resultextracurricular()
    {
        return $this->hasMany(ResultExtraCurricular::class);
    }

    public function abacus()
    {
        return $this->hasOne(Abacus::class);
    }

    public function psychomotorperformance()
    {
        return $this->hasMany(PsychomotorPerformance::class);
    }

    public function pupilreport()
    {
        return $this->hasMany(PupilReport::class);
    }
}
