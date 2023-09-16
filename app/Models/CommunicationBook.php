<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class CommunicationBook extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'title',
        'urgency',
        'student_id',
        'admission_number',
        'message',
        'sender',
        'status',
    ];
}
