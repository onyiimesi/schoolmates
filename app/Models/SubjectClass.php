<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'term',
        'session',
        'class_id',
        'class_name',
        'subject'
    ];

    public function subjects()
    {
        return $this->belongsTo(Subject::class);
    }

}
