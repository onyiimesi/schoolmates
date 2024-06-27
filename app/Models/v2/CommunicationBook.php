<?php

namespace App\Models\v2;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationBook extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'class_id',
        'sender_id',
        'sender_type',
        'subject',
        'message',
        'pinned',
        'file',
        'file_name',
        'file_id',
        'status'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function messages()
    {
        return $this->hasMany(CommunicationBookMessage::class);
    }

    public function replies()
    {
        return $this->hasMany(CommunicationBookReply::class);
    }
}
