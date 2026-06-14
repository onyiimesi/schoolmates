<?php

namespace App\Models\v2;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $communication_book_id
 * @property int $sender_id
 * @property string $sender_type
 * @property int $receiver_id
 * @property string $receiver_type
 * @property string $message
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\CommunicationBook $communicationBook
 * @property-read Model|\Eloquent $receiver
 * @property-read Model|\Eloquent $sender
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereCommunicationBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookReply whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'communication_book_id',
    'sender_id',
    'sender_type',
    'receiver_id',
    'receiver_type',
    'message',
    'status'
])]
class CommunicationBookReply extends Model
{
    use HasFactory;

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
