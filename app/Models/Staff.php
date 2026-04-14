<?php

namespace App\Models;

use App\Enum\StaffStatus;
use App\Models\v2\LessonNote;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $sch_id
 * @property string $campus
 * @property int $designation_id
 * @property string $department
 * @property string $surname
 * @property string $firstname
 * @property string $middlename
 * @property string $username
 * @property string $email
 * @property string $gender
 * @property string $phoneno
 * @property string $address
 * @property string $image
 * @property string $password
 * @property string $pass_word
 * @property string $class_assigned
 * @property string $sub_class
 * @property string $signature
 * @property string $status
 * @property string $teacher_type
 * @property string $campus_type
 * @property string $is_preschool
 * @property string $file_id
 * @property string $sig_id
 */
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

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function designation()
    {
        return $this->belongsTo(Designation::class, 'designation_id');
    }

    public function result()
    {
        return $this->hasMany(Result::class, 'teacher_id');
    }

    public function subjectteacher()
    {
        return $this->hasMany(SubjectTeacher::class, 'staff_id');
    }

    public function lessonnotes()
    {
        return $this->hasMany(LessonNote::class, 'staff_id');
    }

    public function staffScanAttendances()
    {
        return $this->hasMany(StaffScanAttendance::class, 'staff_id');
    }

    public function getCampus()
    {
        return Campus::where('name', $this->campus)->first() ?? null;
    }

    public function getHosAttribute()
    {
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
}
