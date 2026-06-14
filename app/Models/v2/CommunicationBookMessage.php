<?php

namespace App\Models\v2;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $communication_book_id
 * @property int $receiver_id
 * @property string $receiver_type
 * @property string|null $admission_number
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\v2\CommunicationBook $communicationbook
 * @property-read Staff|null $staff
 * @property-read Student|null $student
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereCommunicationBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereReceiverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereReceiverType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|CommunicationBookMessage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'receiver_id',
    'receiver_type',
    'admission_number',
    'status'
])]
class CommunicationBookMessage extends Model
{
    use HasFactory;

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
