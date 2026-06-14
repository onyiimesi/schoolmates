<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $fingerprint
 * @property string|null $connection
 * @property string $sql
 * @property string $raw_sql
 * @property array<array-key, mixed>|null $bindings
 * @property int $time
 * @property int $occurrences
 * @property int $max_time
 * @property int $avg_time
 * @property string|null $file
 * @property int|null $line
 * @property \Illuminate\Support\Carbon|null $first_seen_at
 * @property \Illuminate\Support\Carbon|null $last_seen_at
 * @property bool $resolved
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereAvgTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereBindings($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereConnection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereFingerprint($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereFirstSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereLastSeenAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereMaxTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereOccurrences($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereRawSql($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereSql($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SlowQuery whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'fingerprint','connection','sql','raw_sql','bindings','time',
    'occurrences','max_time','avg_time','file','line',
    'first_seen_at','last_seen_at','resolved',
])]
class SlowQuery extends Model
{
    protected function casts(): array
    {
        return [
            'bindings' => 'array',
            'first_seen_at' => 'datetime',
            'last_seen_at'  => 'datetime',
            'resolved'      => 'boolean',
        ];
    }
}
