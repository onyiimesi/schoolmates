<?php

namespace App\Services\Cbt;

use App\Http\Resources\v2\CbtAnswerResource;
use App\Http\Resources\v2\CbtQuestionResource;
use App\Http\Resources\v2\CbtResultResource;
use App\Http\Resources\v2\CbtSettingsResource;
use App\Models\ClassModel;
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
        $classid = ClassModel::where([
            "sch_id" => $user->sch_id,
            "campus" => $user->campus,
            "class_name" => $user->class_assigned
        ])->value('id');

        try {
            DB::transaction(function() use($user, $classid, $request) {
                CbtQuestion::create([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'class_id' => $classid,
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

    public function addBulkCbtQuestions($user, $request)
    {
        $classid = ClassModel::where([
            "sch_id" => $user->sch_id,
            "campus" => $user->campus,
            "class_name" => $user->class_assigned
        ])->value('id');

        if (! $classid) {
            return $this->error(null, "Class not found", 404);
        }

        try {
            $parsed = $this->parseBulkQuestions($request->questions);

            if (empty($parsed['questions'])) {
                return $this->error(null, "No questions could be detected. Please check the format and try again.", 422);
            }

            if (!empty($parsed['errors'])) {
                return $this->error($parsed['errors'], "Some questions are incomplete. Please fix them and try again.", 422);
            }

            $startNumber = CbtQuestion::where([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'class_id' => $classid,
                    'subject_id' => $request->subject_id,
                    'question_type' => $request->question_type,
                ])
                ->pluck('question_number')
                ->map(fn ($number) => (int) $number)
                ->max() ?? 0;

            $number = $startNumber + 1;
            $now = Carbon::now();
            $rows = [];

            foreach ($parsed['questions'] as $question) {
                $rows[] = [
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'class_id' => $classid,
                    'cbt_setting_id' => $request->cbt_setting_id,
                    'teacher_id' => $user->id,
                    'subject_id' => $request->subject_id,
                    'question_type' => $request->question_type,
                    'question' => $question['question'],
                    'option1' => $question['option1'],
                    'option2' => $question['option2'],
                    'option3' => $question['option3'],
                    'option4' => $question['option4'],
                    'answer' => $question['answer'],
                    'question_mark' => $request->question_mark,
                    'question_number' => (string) $number,
                    'status' => 'unpublished',
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $number++;
            }

            DB::transaction(function () use ($rows) {
                CbtQuestion::insert($rows);
            });

            return $this->success([
                'total' => count($rows),
                'start_number' => $startNumber + 1,
                'end_number' => $number - 1,
            ], count($rows) . " questions added successfully", 201);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function getAllQuestions($user, $request)
    {
        $class = $user->designation_id == 7 ? $user->present_class : $user->class_assigned;

        $classid = ClassModel::where([
            "sch_id" => $user->sch_id,
            "campus" => $user->campus,
            "class_name" => $class
        ])->value('id');

        if (!$classid) {
            return $this->error(null, "Class not found", 404);
        }

        $data = CbtQuestion::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
                'class_id' => $classid,
                'subject_id' => $request->subject_id,
                'question_type' => $request->question_type,
            ])
            ->get();

        $res = CbtQuestionResource::collection($data);

        return $this->success($res, "Successful", 200);
    }


    public function updateQuestion($user, $request)
    {
        $data = $request->json()->all();

        try {
            foreach ($data as $item) {
                $data = CbtQuestion::where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus)
                    ->where('id', $item['id'])
                    ->first();

                if(! $data) {
                    return $this->error(null, "Not found", 404);
                }

                $data->update([
                    'question' => $item['question'],
                    'option1' => $item['option1'],
                    'option2' => $item['option2'],
                    'option3' => $item['option3'],
                    'option4' => $item['option4'],
                    'answer' => $item['answer'],
                    'question_mark' => $item['question_mark'],
                    'question_number' => $item['question_number'],
                    'status' => $item['status']
                ]);
            }

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

    /**
     * Parse a pasted block of objective questions into structured questions.
     *
     * Supported format (each option on its own line, the correct option marked
     * with its letter in brackets at the end of the option text):
     *
     *   Choose the word that is opposite in meaning to "beautiful."
     *   (A) Ugly (A)
     *   (B) Attractive
     *   (C) Pretty
     *   (D) Lovely
     *
     * @return array{questions: array<int, array<string, string>>, errors: array<int, array<string, string>>}
     */
    private function parseBulkQuestions(?string $text): array
    {
        $questions = [];
        $errors = [];
        $current = $this->emptyBulkQuestion();
        $expectedLetter = 'A';

        // Normalize line endings and the non-breaking/unicode spaces Word & Docs add when copying.
        $text = str_replace(
            ["\r\n", "\r", "\xc2\xa0", "\xe2\x80\xaf", "\xe2\x80\x87"],
            ["\n", "\n", ' ', ' ', ' '],
            (string) $text
        );

        foreach (explode("\n", $text) as $rawLine) {
            $line = trim($rawLine);

            if ($line === '') {
                continue;
            }

            $option = $this->matchBulkOptionLine($line);

            if ($option !== null && $option['letter'] === $expectedLetter) {
                $current['options'][$option['letter']] = $option['text'];

                if ($option['marker'] !== null) {
                    $current['answer'] = $option['marker'];
                }

                $expectedLetter = chr(ord($expectedLetter) + 1);
                continue;
            }

            // Any non-option line after we started collecting means a new question begins.
            if (!empty($current['options']) || !empty($current['question'])) {
                $this->finalizeBulkQuestion($current, $questions, $errors);
                $current = $this->emptyBulkQuestion();
                $expectedLetter = 'A';
            }

            $current['question'][] = $this->stripQuestionNumber($line);
        }

        $this->finalizeBulkQuestion($current, $questions, $errors);

        return ['questions' => $questions, 'errors' => $errors];
    }

    /**
     * @return array{question: array<int, string>, options: array<string, string>, answer: ?string}
     */
    private function emptyBulkQuestion(): array
    {
        return ['question' => [], 'options' => [], 'answer' => null];
    }

    /**
     * Match a single option line such as "(A) Ugly (A)", "B) Attractive" or "C. Pretty".
     *
     * @return array{letter: string, text: string, marker: ?string}|null
     */
    private function matchBulkOptionLine(string $line): ?array
    {
        if (!preg_match('/^\s*[\(\[]?\s*([A-Da-d])\s*[\)\].:\-]\s*(.*)$/u', $line, $matches)) {
            return null;
        }

        $letter = strtoupper($matches[1]);
        $text = trim($matches[2]);
        $marker = null;

        // A trailing "(A)" / "[A]" marks the correct option.
        if (preg_match('/[\(\[]\s*([A-Da-d])\s*[\)\]]\s*$/u', $text, $markerMatches)) {
            $marker = strtoupper($markerMatches[1]);
            $text = trim(preg_replace('/[\(\[]\s*[A-Da-d]\s*[\)\]]\s*$/u', '', $text));
        }

        return ['letter' => $letter, 'text' => $text, 'marker' => $marker];
    }

    /**
     * Remove a leading question number such as "1.", "2)" or "(3)".
     */
    private function stripQuestionNumber(string $line): string
    {
        return preg_replace('/^\s*(?:Q(?:uestion)?\s*)?(?:[\(\[]\s*\d+\s*[\)\]]|\d+\s*[\.\):])\s+/iu', '', $line) ?? $line;
    }

    /**
     * Validate a parsed question and append it to the result set (or record an error).
     */
    private function finalizeBulkQuestion(array $current, array &$questions, array &$errors): void
    {
        $questionText = trim(implode(' ', $current['question']));

        if ($questionText === '' && empty($current['options'])) {
            return;
        }

        if ($questionText === '') {
            $errors[] = ['question' => '(missing question text)', 'reason' => 'Question text is missing.'];
            return;
        }

        $missing = [];
        foreach (['A', 'B', 'C', 'D'] as $letter) {
            if (!isset($current['options'][$letter]) || $current['options'][$letter] === '') {
                $missing[] = $letter;
            }
        }

        if (!empty($missing)) {
            $errors[] = ['question' => $questionText, 'reason' => 'Missing option(s): ' . implode(', ', $missing) . '.'];
            return;
        }

        $answerLetter = $current['answer'];

        if ($answerLetter === null || !isset($current['options'][$answerLetter])) {
            $errors[] = [
                'question' => $questionText,
                'reason' => 'Correct answer not marked. Add the correct option letter in brackets e.g. (A) at the end of the correct option.'
            ];
            return;
        }

        $questions[] = [
            'question' => $questionText,
            'option1' => $current['options']['A'],
            'option2' => $current['options']['B'],
            'option3' => $current['options']['C'],
            'option4' => $current['options']['D'],
            'answer' => $current['options'][$answerLetter],
        ];
    }

}




