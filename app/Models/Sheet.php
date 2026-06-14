<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $section
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet whereSection($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Sheet whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Table(name: "sheets")]
class Sheet extends Model
{
}
