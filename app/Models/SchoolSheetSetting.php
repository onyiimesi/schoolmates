<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

/**
 * @property int $id
 * @property string $sch_id
 * @property string $campus
 * @property string $period
 * @property string $term
 * @property array<array-key, mixed> $sheet_ids
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read mixed $sheet_names
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Sheet> $sheets
 * @property-read int|null $sheets_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereCampus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting wherePeriod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereSchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereSheetIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SchoolSheetSetting whereUpdatedAt($value)
 * @mixin \Eloquent
 */
#[\Illuminate\Database\Eloquent\Attributes\Fillable([
    'sch_id',
    'campus',
    'period',
    'term',
    'sheet_ids'
])]
class SchoolSheetSetting extends Model
{
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
            $ids = (new \Illuminate\Support\Collection($this->sheet_ids))->flatten()->toArray();

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
    protected function casts(): array
    {
        return [
            'sheet_ids' => 'array',
        ];
    }

}
