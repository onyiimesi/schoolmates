<?php

namespace App\Models;

use App\Enum\StaffStatus;
use App\Models\v2\LessonNote;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string|null $campus_type
 * @property int $designation_id
 * @property string|null $department
 * @property string|null $surname
 * @property string|null $firstname
 * @property string|null $middlename
 * @property string|null $username
 * @property string|null $email
 * @property string|null $gender
 * @property string|null $phoneno
 * @property string|null $address
 * @property string|null $image
 * @property string|null $class_assigned
 * @property string|null $sub_class
 * @property string|null $class_type
 * @property string|null $is_preschool
 * @property string|null $signature
 * @property string|null $teacher_type
 * @property string|null $file_id
 * @property string|null $sig_id
 * @property string|null $password
 * @property string $pass_word
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Designation|null $designation
 * @property-read mixed $hos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, LessonNote> $lessonnotes
 * @property-read int|null $lessonnotes_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $result
 * @property-read int|null $result_count
 * @property-read \App\Models\Schools|null $school
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\StaffScanAttendance> $staffScanAttendances
 * @property-read int|null $staff_scan_attendances_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubjectTeacher> $subjectTeachers
 * @property-read int|null $subject_teachers_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\StaffFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCampusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereClassAssigned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereClassType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereDepartment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereDesignationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereFirstname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereIsPreschool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereMiddlename($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff wherePhoneno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSubClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereSurname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereTeacherType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Staff whereUsername($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'designation_id',
    'department',
    'surname',
    'firstname',
    'middlename',
    'username',
    'email',
    'gender',
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
])]
class Staff extends Authenticatable implements Auditable
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

    public function school(): BelongsTo
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function result(): HasMany
    {
        return $this->hasMany(Result::class, 'teacher_id');
    }

    public function subjectTeachers(): HasMany
    {
        return $this->hasMany(SubjectTeacher::class, 'staff_id');
    }

    public function lessonnotes(): HasMany
    {
        return $this->hasMany(LessonNote::class, 'staff_id');
    }

    public function staffScanAttendances(): HasMany
    {
        return $this->hasMany(StaffScanAttendance::class, 'staff_id');
    }

    public function getCampus(): ?Campus
    {
        return Campus::where('name', $this->campus)->first() ?? null;
    }

    protected function hos(): Attribute
    {
        return Attribute::make(get: function () {
            $hos = self::select('id', 'surname', 'firstname', 'middlename', 'signature')
                ->where('sch_id', $this->sch_id)
                ->where('campus', $this->campus)
                ->where('designation_id', 3)
                ->where('status', StaffStatus::ACTIVE)
                ->first();
            if (! $hos) {
                return null;
            }
            return $hos;
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

    public static function generateUsername(string $firstname, string $surname): string
    {
        $baseUsername = strtolower($firstname . '.' . $surname);
        $username = $baseUsername;
        $counter = 1;

        while (self::where('username', $username)->exists()) {
            $username = "{$baseUsername}{$counter}";
            $counter++;
        }

        return $username;
    }

    public function fullName(): string
    {
        return trim("{$this->firstname} {$this->middlename} {$this->surname}");
    }
}
