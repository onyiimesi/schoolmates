<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $schname
 * @property string $sch_slug
 * @property string $schlocation
 * @property string|null $country
 * @property string $schaddr
 * @property string $schphone
 * @property string $schemail
 * @property string $schmotto
 * @property string|null $schwebsite
 * @property string|null $schlogo
 * @property string|null $dos
 * @property string|null $password
 * @property string|null $hpsw
 * @property string|null $home
 * @property string|null $folder
 * @property string|null $remark
 * @property string $signed_up
 * @property string|null $pricing_id
 * @property bool $auto_generate
 * @property string|null $admission_number_initial
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property numeric|null $amount_per_student
 * @property-read \App\Models\Subscription|null $activeSubscription
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Campus> $campuses
 * @property-read int|null $campuses_count
 * @property-read \App\Models\AcademicPeriod|null $currentAcademicPeriod
 * @property-read \App\Models\Pricing|null $pricing
 * @property-read \App\Models\SchoolPayment|null $schoolPayment
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Staff> $staffs
 * @property-read int|null $staffs_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereAdmissionNumberInitial($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereAmountPerStudent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereAutoGenerate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereDos($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereFolder($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereHome($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereHpsw($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools wherePricingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereRemark($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchaddr($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchemail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchlocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchlogo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchmotto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSchwebsite($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereSignedUp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Schools whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'schname',
    'schlocation',
    'schaddr',
    'schphone',
    'schemail',
    'schmotto',
    'schwebsite',
    'schlogo',
    'password',
    'hpsw',
    'dome',
    'folder',
    'remark',
    'signed_up',
    'status',
    'dos',
    'auto_generate',
    'admission_number_initial',
    'pricing_id',
])]
class Schools extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    public function schoolPayment()
    {
        return $this->hasOne(SchoolPayment::class, 'sch_id', 'sch_id');
    }

    public function campuses()
    {
        return $this->hasMany(Campus::class, 'sch_id', 'sch_id');
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class, 'sch_id', 'sch_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'sch_id', 'sch_id');
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class, 'pricing_id', 'id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'school_id', 'id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'school_id', 'id')
            ->where('status', 'active')
            ->whereDate('ends_at', '>=', now());
    }

    public function currentAcademicPeriod()
    {
        return $this->hasOne(AcademicPeriod::class, 'sch_id', 'sch_id')
            ->where('is_current_period', true);
    }
    protected function casts(): array
    {
        return [
            'auto_generate' => 'boolean'
        ];
    }
}
