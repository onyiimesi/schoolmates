<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $file
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StudentExcelImport whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'file'
])]
class StudentExcelImport extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
