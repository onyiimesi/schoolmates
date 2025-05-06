<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class SchoolSheetSetting extends Model
{
    protected $fillable = [
        'sch_id',
        'campus',
        'period',
        'term',
        'sheet_ids'
    ];

    protected $casts = [
        'sheet_ids' => 'array',
    ];

    protected function period(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords(str_replace('-', ' ', $value)),
            set: fn (string $value) => str_replace(' ', '-', strtolower($value)),
        );
    }

    protected function term(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => ucwords(str_replace('-', ' ', $value)),
            set: fn (string $value) => str_replace(' ', '-', strtolower($value)),
        );
    }

    protected function sheetNames(): Attribute
    {
        return Attribute::get(function () {
            $ids = collect($this->sheet_ids)->flatten()->toArray();

            return Sheet::whereIn('id', $ids)
                    ->get()
                    ->map(function ($section, $id) {
                        return [
                            'id' => $section->id,
                            'section' => $section->section,
                        ];
                    })
                    ->toArray();
        });
    }

    public function sheets()
    {
        return $this->hasMany(Sheet::class, 'id', 'sheet_ids');
    }

}
