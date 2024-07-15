<?php

namespace App\Models\v2;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationBookMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'receiver_id',
        'receiver_type',
        'admission_number',
        'status'
    ];

    public function communicationbook()
    {
        return $this->belongsTo(CommunicationBook::class, 'communication_book_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'receiver_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'receiver_id');
    }
}
