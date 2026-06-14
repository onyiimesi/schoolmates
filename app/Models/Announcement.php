<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $priority
 * @property string|null $title
 * @property string $description
 * @property array<array-key, mixed> $schools
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereSchools($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Announcement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'priority',
    'title',
    'description',
    'schools',
    'expiry_date',
    'status',
])]
#[\Illuminate\Database\Eloquent\Attributes\Hidden([
    'schools',
    'expiry_date',
    'created_at',
    'updated_at',
])]
class Announcement extends Model
{
    protected function casts(): array
    {
        return [
            'schools' => 'array',
            'expiry_date' => 'date',
        ];
    }
}
