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
        $settings = ScoreOption::select('id', 'label', 'is_default', 'assessment_type')->get();
        return $this->success($settings, 'Settings');
    }

    public function storeSettings($request)
    {
        $user = userAuth();

        $scoreOption = ScoreOption::findOrFail($request->score_option_id);

        $segments = array_map('trim', explode('-', $request->value));

        $allNumeric = collect($segments)->every(fn($part) => is_numeric($part));

        if (!$allNumeric) {
            return $this->error(null, 'All score segments must be numeric', 422);
        }

        $assessmentSegments = array_slice($segments, 0, -1);

        if (count($assessmentSegments) != $scoreOption->assessment_type) {
            return $this->error(
                null,
                "Invalid score breakdown. Expected {$scoreOption->assessment_type} continuous assessment segments before the exam score.",
                422
            );
        }

        if (array_sum($assessmentSegments) !== 40) {
            return $this->error(null, 'Assessment segments must sum up to 40', 422);
        }

        $data = SchoolScoreSetting::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $request->campus
            ],
            [
                'score_option_id' => $request->score_option_id,
                'value_score' => $request->value
            ]
        );

        $msg = $data->wasRecentlyCreadted ? 'Settings saved successfully' : 'Settings updated successfuly';

        return $this->success(null, $msg);
    }

    public function getSchoolScoreSettings()
    {
        $sch_id = request()->query('sch_id');
        $campus = request()->query('campus');

        if (!$sch_id || !$campus) {
            return $this->error(null, 'school id and campus are required', 422);
        }

        $setting = SchoolScoreSetting::with('scoreOption')
            ->where('sch_id', $sch_id)
            ->where('campus', $campus)
            ->first();

        if (!$setting || !$setting->scoreOption) {
            return $this->error(null, 'No settings found', 404);
        }

        $scoreOption = [
            'id' => $setting->scoreOption->id,
            'label' => $setting->scoreOption->label,
            'segments' => $setting->scoreOption->segments,
            'assessment_type' => (int)$setting->scoreOption->assessment_type,
            'is_default' => $setting->scoreOption->is_default,
        ];

        $data = [
            'score_option' => $scoreOption,
            'value' => $setting->value_score,
        ];

        return $this->success($data, 'Settings');
    }

    public function getSheetSections()
    {
        $sheets = Sheet::select('id', 'section')->get();
        return $this->success($sheets, 'Sheet sections');
    }

    public function saveSheetSections($request)
    {
        $user = userAuth();

        $period = strtolower(str_replace(' ', '-', $request->period));
        $term = strtolower(str_replace(' ', '-', $request->term));

        SchoolSheetSetting::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $request->campus,
                'period' => $period,
                'term' => $term,
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
        $period = request()->query('period');
        $term = request()->query('term');

        $period = $period ? strtolower(str_replace(' ', '-', $period)) : null;
        $term = $term ? strtolower(str_replace(' ', '-', $term)) : null;

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

