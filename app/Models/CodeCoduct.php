<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CodeCoduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule', 
        'description', 
        'apply_to', 

    ];
}
