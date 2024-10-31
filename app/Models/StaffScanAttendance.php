<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffScanAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'session',
        'staff_id',
        'time_in',
        'date_in',
        'time_out',
        'date_out',
        'ip_address',
        'device',
        'os',
        'location',
        'status',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'location' => 'array'
        ];
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
