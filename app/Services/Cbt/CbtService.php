<?php

namespace App\Services\Cbt;

use App\Http\Resources\v2\CbtQuestionResource;
use App\Http\Resources\v2\CbtSettingsResource;
use App\Models\v2\CbtQuestion;
use App\Models\v2\CbtSetting;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Facades\DB;

class CbtService {

    use HttpResponses;

    public function setup($user, $request)
    {
        try {
            DB::transaction(function () use ($user, $request) {
                $period = $request->period;
                $term = $request->term;
                $session = $request->session;
                $subject_id = $request->subject_id;
                $question_type = $request->question_type;

                CbtSetting::updateOrCreate(
                    [
                        'sch_id' => $user->sch_id,
                        'campus' => $user->campus,
                        'period' => $period,
                        'term' => $term,
                        'session' => $session,
                        'subject_id' => $subject_id,
                        'question_type' => $question_type
                    ],
                    [
                        'instruction' => $request->instruction,
                        'duration' => $request->duration,
                        'mark' => $request->mark,
                    ]
                );
            });

            return $this->success(null, "Successful", 200);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function getSettings($user, $request)
    {
        $data = CbtSetting::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('subject_id', $request->subject_id)
        ->where('question_type', $request->question_type)
        ->first();

        if(!$data){
            return $this->error(null, "Not found!", 404);
        }

        $data = new CbtSettingsResource($data);

        return $this->success($data, "Successful", 200);
    }

    public function addCbtQuestion($user, $request)
    {
        try {
            DB::transaction(function() use($user, $request) {
                CbtQuestion::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'cbt_setting_id' => $request->cbt_setting_id,
                    'teacher_id' => $user->id,
                    'subject_id' => $request->subject_id,
                    'question_type' => $request->question_type,
                    'question' => $request->question,
                    'option1' => $request->option1,
                    'option2' => $request->option2,
                    'option3' => $request->option3,
                    'option4' => $request->option4,
                    'answer' => $request->answer,
                    'question_mark' => $request->question_mark,
                    'status' => 'active',
                ]);
            });

            return $this->success(null, "Successful", 200);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function getAllQuestions($user, $request)
    {
        $data = CbtQuestion::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('subject_id', $request->subject_id)
        ->where('question_type', $request->question_type)
        ->get();

        if(!$data){
            return $this->error(null, "Not found!", 404);
        }

        $data = CbtQuestionResource::collection($data);

        return $this->success($data, "Successful", 200);
    }

}




