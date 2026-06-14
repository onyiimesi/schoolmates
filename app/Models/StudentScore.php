<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $subject
 * @property string $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Result $result
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentScore whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'subject',
    'score'
])]
class StudentScore extends Model
{
    use HasFactory;

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

}
