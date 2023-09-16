<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class HealthReport extends Model implements Auditable
{ 
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'admission_number',
        'student_id',
        'student_fullname',
        'date_of_incident',
        'time_of_incident',
        'condition',
        'state',
        'report_details',
        'action_taken',
        'recommendation'

    ];
}
