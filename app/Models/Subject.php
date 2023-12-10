<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Subject extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
        'sch_id',
        'campus',
        'class_name',
        'subject',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function subjectclass()
    {
        return $this->belongsTo(SubjectClass::class);
    }
}
