<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $name
 * @property string|null $slug
 * @property string $phoneno
 * @property string $email
 * @property string|null $image
 * @property string|null $file_id
 * @property string $address
 * @property string $state
 * @property string|null $campus_type
 * @property bool|null $is_preschool
 * @property string $status
 * @property string $created_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \App\Models\Schools|null $school
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereCampusType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereFileId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereIsPreschool($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus wherePhoneno($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Campus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'name',
    'slug',
    'email',
    'image',
    'file_id',
    'phoneno',
    'address',
    'state',
    'campus_type',
    'is_preschool',
    'status',
    'created_by'
])]
class Campus extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected function casts(): array
    {
        return [
            'is_preschool' => 'boolean'
        ];
    }

    protected function isPreschool(): Attribute
    {
        return Attribute::make(
            get: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            set: fn($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, "sch_id", "sch_id");
    }
}
