<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $result_id
 * @property string $name
 * @property string $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereResultId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AffectiveDisposition whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'name',
    'score'
])]
class AffectiveDisposition extends Model
{
    use HasFactory;
}
