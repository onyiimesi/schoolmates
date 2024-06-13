<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationBookMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'admission_number',
        'subject',
        'message',
        'pinned',
        'file',
        'file_name',
        'file_id',
    ];

    public function communicationbook()
    {
        return $this->belongsTo(CommunicationBook::class, 'communication_book_id');
    }
}
