<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreSchoolResultExtraCurricular extends Model
{
    use HasFactory;

    protected $fillable = [
        'pre_school_result_id',
        'name',
        'value'
    ];
}
