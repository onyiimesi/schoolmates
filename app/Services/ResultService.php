<?php

namespace App\Services;

use App\Models\SchoolScoreSetting;
use App\Models\SchoolSheetSetting;
use App\Models\ScoreOption;
use App\Models\Sheet;
use App\Traits\HttpResponses;

class ResultService
{
    use HttpResponses;

    public function getSettings()
    {
        $settings = ScoreOption::select('id', 'label', 'is_default')->get();
        return $this->success($settings, 'Settings');
    }

    public function storeSettings($request)
    {
        $user = userAuth();

        $data = SchoolScoreSetting::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $request->campus
            ],
            [
                'score_option_id' => $request->score_option_id
            ]
        );

        $msg = $data->wasRecentlyCreadted ? 'Settings saved successfully' : 'Settings updated successfuly';

        return $this->success(null, $msg);
    }

    public function getSchoolScoreSettings()
    {
        $sch_id = request()->query('sch_id');
        $campus = request()->query('campus');

        $setting = SchoolScoreSetting::with('scoreOption')
            ->when($sch_id && $campus, function ($query) use ($sch_id, $campus) {
                $query->where('sch_id', $sch_id)
                    ->where('campus', $campus);
            })
            ->first();

        if (!$setting || !$setting->scoreOption) {
            return $this->error(null, 'No settings found', 404);
        }

        $scoreOption = [
            'id' => $setting->scoreOption->id,
            'label' => $setting->scoreOption->label,
            'segments' => $setting->scoreOption->segments,
            'is_default' => $setting->scoreOption->is_default,
        ];

        return $this->success($scoreOption, 'Settings');
    }
    public function getSheetSections()
    {
        $sheets = Sheet::select('id', 'section')->get();
        return $this->success($sheets, 'Sheet sections');
    }

    public function saveSheetSections($request)
    {
        $request->validate([
            'campus' => 'required|string',
            'period' => 'required|string',
            'term' => 'required|string',
            'sheet_ids' => 'required|array',
            'sheet_ids.*' => 'required|integer|exists:sheets,id',
        ]);

        $user = userAuth();

        SchoolSheetSetting::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $request->campus,
                'period' => $request->period,
                'term' => $request->term,
            ],
            [
                'sheet_ids' => $request->sheet_ids,
            ]
        );

        return $this->success(null, 'Sheet sections saved successfully');
    }

    public function getSchoolSheetSettings()
    {
        $sch_id = request()->query('sch_id');
        $campus = request()->query('campus');
        $period = ucwords(str_replace('-', ' ', request()->query('period')));
        $term = ucwords(str_replace('-', ' ', request()->query('term')));

        $setting = SchoolSheetSetting::when($sch_id && $campus, function ($query) use ($sch_id, $campus, $period, $term) {
                $query->where('sch_id', $sch_id)
                    ->where('campus', $campus)
                    ->where('period', $period)
                    ->where('term', $term);
            })
            ->first();

        if (!$setting) {
            return $this->error(null, 'No settings found', 404);
        }

        $data = [
            'id' => $setting->id,
            'sch_id' => $setting->sch_id,
            'campus' => $setting->campus,
            'period' => $setting->period,
            'term' => $setting->term,
            'sheet_sections' => $setting->sheet_names,
        ];

        return $this->success($data, 'Settings');
    }
}

