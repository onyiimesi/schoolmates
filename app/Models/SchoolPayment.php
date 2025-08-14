<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPayment extends Model
{
    protected $table = 'school_payments';

    use HasFactory;

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'sch_id', 'sch_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'sch_id', 'sch_id');
    }
}
