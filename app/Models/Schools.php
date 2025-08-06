<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class Schools extends Model implements Auditable
{
    use HasFactory;
    use \OwenIt\Auditing\Auditable;

    protected $fillable = [
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
    ];

    protected $casts = [
        'auto_generate' => 'boolean'
    ];

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
}
