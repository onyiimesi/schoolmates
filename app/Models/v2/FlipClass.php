<?php

namespace App\Models\v2;

use App\Models\ClassModel;
use App\Models\Staff;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlipClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'staff_id',
        'week',
        'subject_id',
        'class_id',
        'topic',
        'description',
        'video_url',
        'file',
        'file_name',
        'file_id',
        'submitted_by',
        'status',
        'date_submitted',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function class()
    {
        return $this->belongsTo(ClassModel::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
