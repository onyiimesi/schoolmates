<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommunicationBookReply extends Model
{
    protected $table = "communication_book_replies";

    use HasFactory;

    protected $fillable = [
        'communication_book_id',
        'sender_id',
        'sender_type',
        'receiver_id',
        'receiver_type',
        'message',
        'status'
    ];

    public function communicationBook()
    {
        return $this->belongsTo(CommunicationBook::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    public function receiver()
    {
        return $this->morphTo();
    }
}
