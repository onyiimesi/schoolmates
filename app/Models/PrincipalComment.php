<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $hos_id
 * @property string $hos_fullname
 * @property string $hos_comment
 * @property string|null $signature
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereHosComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereHosFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereHosId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereSignature($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PrincipalComment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'hos_id',
    'hos_fullname',
    'hos_comment',
    'signature',
])]
class PrincipalComment extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
