<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SchoolScoreSetting extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'score_option_id'
    ];

    public function scoreOption()
    {
        return $this->belongsTo(ScoreOption::class);
    }

    public function scopeByCampus($query, $campus)
    {
        return $query->where('sch_id', Auth::user()->sch_id)
            ->where('campus', $campus);
    }
}
