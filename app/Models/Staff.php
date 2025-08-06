<?php

namespace App\Models;

use App\Models\v2\LessonNote;
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
