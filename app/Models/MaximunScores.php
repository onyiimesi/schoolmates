<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class MaximunScores extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'midterm', 
        'exam', 
        'total',
        'sch_id',
        'campus',
        'first_assessment',
        'second_assessment',
        'has_two_assessment'
    ];
}
