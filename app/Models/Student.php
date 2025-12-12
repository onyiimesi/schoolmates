<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
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
        'campus_type',
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
        'created_by',
        'is_preschool',
        'file_id',
        'in_present_class',
    ];

    protected static function booted()
    {
        static::saved(function ($staff) {
            Cache::forget("school_population_{$staff->sch_id}");
        });

        static::deleted(function ($staff) {
            Cache::forget("school_population_{$staff->sch_id}");
        });
    }

    protected $hidden = [
        'password',
        'pass_word',
        'updated_at',
    ];

    protected $casts = [
        'in_present_class' => 'boolean',
    ];

    public function assignmentanswer()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    public function getHosAttribute(): ?Staff
    {
        $hos = Staff::select('id', 'surname', 'firstname', 'middlename', 'signature')
            ->where('sch_id', $this->sch_id)
            ->where('campus', $this->campus)
            ->where('designation_id', 3)
            ->first();

        if (! $hos) {
            return null;
        }

        return $hos;
    }

    public function isPreschool(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return in_array($value, [1, '1', true, 'true'], true)
                    ? 'true'
                    : 'false';
            },
            set: function ($value) {
                return in_array($value, [1, '1', true, 'true'], true)
                    ? 'true'
                    : 'false';
            }
        );
    }
}
