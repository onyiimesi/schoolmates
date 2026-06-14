<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $vendor_code
 * @property string $vendor_type
 * @property string $initial_balance
 * @property string $vendor_name
 * @property string $company_name
 * @property string $contact_address
 * @property string $contact_person
 * @property string $contact_phone
 * @property string $email_address
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCompanyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereContactAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereContactPerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereContactPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereEmailAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereInitialBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereVendorCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereVendorName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vendor whereVendorType($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'vendor_code',
    'vendor_type', 
    'initial_balance',
    'vendor_name',        
    'company_name', 
    'contact_address', 
    'contact_person', 
    'contact_phone', 
    'email_address',
])]
class Vendor extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;
}
