<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $admission_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber whereAdmissionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|AdmissionNumber whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'admission_number'
])]
class AdmissionNumber extends Model
{
    use HasFactory;
}
