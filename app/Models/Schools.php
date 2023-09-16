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
    ];
}
