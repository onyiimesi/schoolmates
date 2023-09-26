<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject',
        'score'
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

}
