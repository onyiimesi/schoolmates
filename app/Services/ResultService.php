<?php

namespace App\Services;

use App\Models\SchoolScoreSetting;
use App\Models\ScoreOption;
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

}

