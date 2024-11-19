<?php

namespace App\Services\Cbt;

use App\Http\Resources\v2\CbtAnswerResource;
use App\Http\Resources\v2\CbtQuestionResource;
use App\Http\Resources\v2\CbtResultResource;
use App\Http\Resources\v2\CbtSettingsResource;
use App\Models\v2\CbtAnswer;
use App\Models\v2\CbtPerformance;
use App\Models\v2\CbtQuestion;
use App\Models\v2\CbtResult;
use App\Models\v2\CbtSetting;
use App\Traits\HttpResponses;
use Exception;
use Illuminate\Support\Carbon;
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
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'class_id' => $user->class_id,
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
                    'question_number' => $request->question_number,
                    'status' => 'unpublished',
                ]);
            });

            return $this->success(null, "Successful", 201);
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
            ->where('class_id', $user->class_id)
            ->where('subject_id', $request->subject_id)
            ->where('question_type', $request->question_type)
            ->get();

        if(!$data){
            return $this->error(null, "Not found!", 404);
        }

        $data = CbtQuestionResource::collection($data);

        return $this->success($data, "Successful", 200);
    }

    public function updateQuestion($user, $request, $id)
    {
        try {
            $data = CbtQuestion::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $id)
            ->first();

            if(!$data){
                return $this->error(null, "Not found", 404);
            }

            $data->update([
                'question' => $request->question,
                'option1' => $request->option1,
                'option2' => $request->option2,
                'option3' => $request->option3,
                'option4' => $request->option4,
                'answer' => $request->answer,
                'question_mark' => $request->question_mark,
                'question_number' => $request->question_number,
                'status' => $request->status
            ]);

            return $this->success(null, "Updated Successful");
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function removeQuestion($user, $id)
    {
        $data = CbtQuestion::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('id', $id)
        ->first();

        if(!$data){
            return $this->error(null, "Not found", 404);
        }

        $data->delete();

        return $this->success(null, "Deleted Successful");
    }

    public function createCbtAnswer($user, $request)
    {
        try {
            $data = $request->json()->all();

            DB::transaction(function () use ($user, $data) {
                foreach($data as $item) {
                    CbtAnswer::create([
                        'sch_id' => $user->sch_id,
                        'campus' => $user->campus,
                        'period' => $item['period'],
                        'term' => $item['term'],
                        'session' => $item['session'],
                        'cbt_question_id' => $item['cbt_question_id'],
                        'student_id' => $item['student_id'],
                        'subject_id' => $item['subject_id'],
                        'question' => $item['question'],
                        'question_number' => $item['question_number'],
                        'question_type' => $item['question_type'],
                        'answer' =>  $item['answer'],
                        'correct_answer' =>  $item['correct_answer'],
                        'mark_status' => 0,
                        'submitted' =>  $item['submitted'],
                        'submitted_time' => $item['submitted_time'],
                        'duration' => $item['duration']
                    ]);
                }
            });

            return $this->success(null, "Submitted Successfully");
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function getAnswerSubject($user, $request)
    {
        $data = CbtAnswer::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->question_type)
        ->where('subject_id', $request->subject_id)
        ->get();

        if(!$data){
            return $this->error(null, "Not found!", 404);
        }

        $data = CbtAnswerResource::collection($data);

        return $this->success($data, "Successful");
    }

    public function getAnswerOneStudent($user, $request)
    {
        $data = CbtAnswer::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->question_type)
        ->where('subject_id', $request->subject_id)
        ->where('student_id', $request->student_id)
        ->get();

        if(!$data){
            return $this->error(null, "Not found!", 404);
        }

        $data = CbtAnswerResource::collection($data);

        return $this->success($data, "Successful");
    }

    public function cbtPublish($user, $request)
    {
        try {
            $assign = CbtQuestion::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->where('subject_id', $request->subject_id)
            ->where('question_type', $request->question_type)
            ->get();

            if ($request->is_publish == 1) {
                $assign->each(function ($cbt) {
                    $cbt->update([
                        'status' => 'published'
                    ]);
                });
            } else {
                $assign->each(function ($cbt) {
                    $cbt->update([
                        'status' => 'unpublished'
                    ]);
                });
            }

            return $this->success(null, "Published successfully!");
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function createResult($user, $data)
    {
        try {
            DB::transaction(function () use ($user, $data) {
                $result = $data['result'];
                CbtResult::updateOrCreate([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $result['period'],
                    'term' => $result['term'],
                    'session' => $result['session'],
                    'cbt_answer_id' => $result['cbt_answer_id'],
                    'student_id' => $result['student_id'],
                    'subject_id' => $result['subject_id'],
                    'question_type' => $result['question_type'],
                    'answer_score' => $result['answer_score'],
                    'correct_answer' => $result['correct_answer'],
                    'incorrect_answer' => $result['incorrect_answer'],
                    'unattempted_question' => $result['unattempted_question'],
                    'total_answer' => $result['total_answer'],
                    'student_total_mark' => $result['student_total_mark'],
                    'test_total_mark' => $result['test_total_mark'],
                    'student_duration' => $result['student_duration'],
                    'test_duration' => $result['test_duration']
                ]);

                $performanceData = $data['performance'];
                CbtPerformance::updateOrCreate([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $performanceData['period'],
                    'term' => $performanceData['term'],
                    'session' => $performanceData['session'],
                    'cbt_result_id' => $performanceData['cbt_result_id'],
                    'student_id' => $performanceData['student_id'],
                    'subject_id' => $performanceData['subject_id'],
                    'question_type' => $performanceData['question_type'],
                    'student_total_mark' => $performanceData['student_total_mark'],
                    'correct_answer' => $performanceData['correct_answer'],
                    'incorrect_answer' => $performanceData['incorrect_answer'],
                    'unattempted_question' => $performanceData['unattempted_question'],
                    'total_answer' => $performanceData['total_answer'],
                    'test_total_mark' => $performanceData['test_total_mark'],
                    'student_duration' => $performanceData['student_duration'],
                    'test_duration' => $performanceData['test_duration']
                ]);
            });

            return $this->success(null, "Submitted successfully");
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage());
        }
    }

    public function getStudentResult($user, $request)
    {
        $data = CbtResult::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('question_type', $request->question_type)
        ->where('subject_id', $request->subject_id)
        ->get();

        if(empty($data)){
            return $this->error(null, "Not found!", 404);
        }

        $data = CbtResultResource::collection($data);

        return $this->success($data, "Successful");
    }

    public function getChart($user, $request)
    {
        $period = $request->input('period');
        $term = $request->input('term');
        $session = $request->input('session');
        $studentId = $request->input('student_id');
        $subjectId = $request->input('subject_id');

        $query = DB::table('cbt_performances')
            ->select('student_id', 'student_total_mark', 'test_total_mark', 'student_duration', 'test_duration', 'correct_answer',
            'incorrect_answer', 'unattempted_question', 'total_answer')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('subject_id', $subjectId);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $cbts = $query->groupBy('student_id', 'student_total_mark', 'test_total_mark', 'student_duration', 'test_duration', 'correct_answer', 'incorrect_answer', 'unattempted_question', 'total_answer')
            ->orderBy('student_id')
            ->get();

        $studentsData = [];
        foreach ($cbts as $cbt) {
            $studentId = $cbt->student_id;
            $studentMark = $cbt->student_total_mark;
            $testMark = $cbt->test_total_mark;
            $studentDuration = $cbt->student_duration;
            $testDuration = $cbt->test_duration;
            $correctAnswer = $cbt->correct_answer;
            $incorrectAnswer = $cbt->incorrect_answer;
            $unattempt = $cbt->unattempted_question;
            $totalAnswer = $cbt->total_answer;

            $studentData = [
                'student_id' => $studentId,
                'student_total_mark' => $studentMark,
                'test_total_mark' => $testMark,
                'student_duration' => $studentDuration,
                'test_duration' => $testDuration,
                'correct_answer' => $correctAnswer,
                'incorrect_answer' => $incorrectAnswer,
                'unattempted_question' => $unattempt,
                'total_answer' => $totalAnswer
            ];

            $studentsData[] = $studentData;
        }

        $data[] = [
            'period' => $period,
            'term' => $term,
            'session' => $session,
            'subject_id' => $subjectId,
            'students' => $studentsData
        ];

        return $this->success($data, "Performance Chart", 200);
    }

}




