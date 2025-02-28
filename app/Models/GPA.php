<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GPA extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'min_mark',
        'max_mark',
        'remark',
        'grade_point',
        'key_range'
    ];

    public function school()
    {
        return $this->belongsTo(Schools::class, 'sch_id', 'sch_id');
    }
}
