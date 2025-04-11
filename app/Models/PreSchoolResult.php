<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class PreSchoolResult extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
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
    ];

    protected $casts = [
        'evaluation_report' => 'array',
        'cognitive_development' => 'array'
    ];

    public function preschoolresultextracurricular()
    {
        return $this->hasMany(PreSchoolResultExtraCurricular::class, 'pre_school_result_id', 'id');
    }
}
