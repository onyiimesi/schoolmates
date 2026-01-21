<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolScoreSetting extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'score_option_id',
        'value_score',
        'previous_score_option_id',
        'previous_value_score',
    ];

    public function scoreOption()
    {
        return $this->belongsTo(ScoreOption::class);
    }

    public function scopeByCampus($query, $user)
    {
        return $query->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus);
    }
}
