<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Campus extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'is_preschool' => 'boolean'
        ];
    }

    protected function isPreschool(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
            set: fn ($value) => filter_var($value, FILTER_VALIDATE_BOOLEAN)
        );
    }

    public function school()
    {
        return $this->belongsTo(Schools::class, "sch_id", "sch_id");
    }
}
