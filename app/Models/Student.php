<?php

namespace App\Models;

use App\Enum\StaffStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $designation_id
 * @property string $campus
 * @property string|null $campus_type
 * @property string|null $surname
 * @property string|null $firstname
 * @property string|null $middlename
 * @property string $admission_number
 * @property string $username
 * @property string $password
 * @property string $pass_word
 * @property string|null $genotype
 * @property string|null $blood_group
 * @property string|null $gender
 * @property string|null $dob
 * @property string|null $nationality
 * @property string|null $state
 * @property string|null $session_admitted
 * @property string|null $class
 * @property string|null $class_sub_class
 * @property string|null $present_class
 * @property bool $in_present_class
 * @property string|null $sub_class
 * @property string|null $teacher_surname
 * @property string|null $teacher_firstname
 * @property string|null $teacher_middlename
 * @property string|null $image
 * @property string|null $home_address
 * @property string|null $phone_number
 * @property string|null $email_address
 * @property string $is_preschool
 * @property string|null $file_id
 * @property string $status
 * @property string|null $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AssignmentAnswer> $assignmentanswer
 * @property-read int|null $assignmentanswer_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Staff|null $hos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @property-read \App\Models\Schools|null $school
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereBloodGroup($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCampusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereClassSubClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereGenotype($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereHomeAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereInPresentClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereIsPreschool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereNationality($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student wherePresentClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSessionAdmitted($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSubClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTeacherFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTeacherMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereTeacherSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Student whereUsername($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
])]
#[\Illuminate\Database\Eloquent\Attributes\Hidden([
    'password',
    'pass_word',
    'updated_at',
])]
class Student extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected static function booted()
    {
        static::saved(function ($staff) {
            Cache::forget("school_population_{$staff->sch_id}");
        });

        static::deleted(function ($staff) {
            Cache::forget("school_population_{$staff->sch_id}");
        });
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function assignmentanswer()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    public function results()
    {
        return $this->hasMany(Result::class, 'student_id');
    }

    public function getCampus()
    {
        return Campus::where('name', $this->campus)->first() ?? null;
    }

    protected function hos(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(get: function () {
            return Staff::select('id', 'surname', 'firstname', 'middlename', 'signature')
                ->where('sch_id', $this->sch_id)
                ->where('campus', $this->campus)
                ->where('designation_id', 3)
                ->where('status', StaffStatus::ACTIVE)
                ->first() ?? null;
        });
    }

    protected function isPreschool(): Attribute
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

    protected static function studentCountByClass(User $user, string $class)
    {
        return self::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'present_class' => $class,
        ])->count();
    }
    protected function casts(): array
    {
        return [
            'in_present_class' => 'boolean',
        ];
    }
}
