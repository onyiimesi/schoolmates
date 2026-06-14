<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $plan
 * @property string|null $description
 * @property string|null $price
 * @property string $type
 * @property string $features
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing wherePlan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pricing whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Table(name: 'pricings')]
class Pricing extends Model
{
    use HasFactory;
}
