<?php

namespace App\Models\v2;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property string $session
 * @property int $class_id
 * @property int $sender_id
 * @property string $sender_type
 * @property string $subject
 * @property string $message
 * @property int $pinned
 * @property string|null $file
 * @property string|null $file_id
 * @property string|null $file_name
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\CommunicationBookMessage> $messages
 * @property-read int|null $messages_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\v2\CommunicationBookReply> $replies
 * @property-read int|null $replies_count
 * @property-read Staff|null $staff
 * @property-read Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook wherePinned($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereSenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereSenderType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereSession($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBook whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
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
])]
class CommunicationBook extends Model
{
    use HasFactory;

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'sender_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'sender_id');
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
