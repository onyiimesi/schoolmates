<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class Staff extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'designation_id',
        'department',
        'surname',
        'firstname',
        'middlename',
        'username',
        'email',
        'phoneno',
        'address',
        'image',
        'password',
        'pass_word',
        'class_assigned',
        'sub_class',
        'signature',
        'status',
        'teacher_type',
        'campus_type',
        'is_preschool',
        'file_id',
        'sig_id'
    ];

    public function staff(){
        return $this->belongsTo(Staff::class);
    }

    public function result(){
        return $this->hasMany(Result::class, 'teacher_id');
    }

    public function subjectteacher(){
        return $this->hasMany(SubjectTeacher::class, 'staff_id');
    }
}
