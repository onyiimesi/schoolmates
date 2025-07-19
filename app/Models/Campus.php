<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

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
        'phoneno',
        'address',
        'state',
        'campus_type',
        'is_preschool',
        'status',
        'created_by'
    ];
}
