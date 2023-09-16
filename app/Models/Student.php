<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

class Student extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'designation_id',
        'campus',
        'surname',
        'firstname',
        'middlename',
        'admission_number',
        'username',
        'password',
        'pass_word',
        'genotype',
        'blood_group',
        'gender',
        'dob',
        'nationality',
        'state',
        'session_admitted',
        'class',
        'class_sub_class',
        'present_class',
        'sub_class',
        'image',
        'home_address',
        'phone_number',
        'email_address',
        'status',
        'created_by'
    ];

    public function student(){
        return $this->belongsTo(Student::class);
    }
}
