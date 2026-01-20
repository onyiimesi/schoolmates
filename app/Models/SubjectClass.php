<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property string $sch_id
 * @property string $campus
 * @property string $term
 * @property string $session
 * @property string $class_id
 * @property string $class_name
 * @property string $subject
 */
class SubjectClass extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'class_id',
        'class_name',
        'subject'
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function assignmentResults()
    {
        return $this->hasMany(AssignmentResult::class);
    }

    public function assignmentAnswers()
    {
        return $this->hasMany(AssignmentAnswer::class);
    }

    public function assignmentMarks()
    {
        return $this->hasMany(AssignmentMark::class);
    }

    public function assignmentPerformances()
    {
        return $this->hasMany(AssignmentPerformance::class);
    }
}
