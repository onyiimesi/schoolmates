<?php

namespace App\Services;

use App\Http\Resources\AssignmentAnswerResource;
use App\Http\Resources\AssignmentMarkResource;
use App\Http\Resources\AssignmentResource;
use App\Http\Resources\AssignmentResultResource;
use App\Http\Resources\TheoryResource;
use App\Models\Assignment;
use App\Models\AssignmentAnswer;
use App\Models\AssignmentMark;
use App\Models\AssignmentPerformance;
use App\Models\AssignmentResult;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AssignmentService
{
    use HttpResponses;

    public function objective($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            Assignment::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'teacher_id' => $user->id,
                'question_type' => $item['question_type'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'answer' =>  $item['answer'],
                'subject_id' => $item['subject_id'],
                'option1' => $item['option1'],
                'option2' => $item['option2'],
                'option3' => $item['option3'],
                'option4' => $item['option4'],
                'total_question' => $item['total_question'],
                'question_mark' => $item['question_mark'],
                'total_mark' => $item['total_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Created Successfully', 201);
    }

    public function theory($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {

            if ($item['image']) {
                $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
                $file = $item['image'];
                $imagePath = uploadImage($file, 'assignment', $cleanSchId);
            }

            Assignment::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'teacher_id' => $user->id,
                'question_type' => $item['question_type'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'answer' => $item['answer'],
                'subject_id' => $item['subject_id'],
                'image' => $imagePath['url'] ?? null,
                'total_question' => $item['total_question'],
                'question_mark' => $item['question_mark'],
                'total_mark' => $item['total_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Created Successfully');
    }

    public function assign($request, $user): JsonResponse
    {
        $validated = $request->validate([
            'period' => 'required',
            'term' => 'required',
            'session' => 'required',
            'type' => 'required|in:objective,theory',
            'week' => 'required',
            'subject_id' => 'sometimes|nullable'
        ]);

        [$period, $term, $session, $type, $week, $subjectId] = [
            $validated['period'],
            $validated['term'],
            $validated['session'],
            $validated['type'],
            $validated['week'],
            $validated['subject_id'] ?? null
        ];

        $assign = Assignment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $period)
            ->where('term', $term)
            ->where('session', $session)
            ->where('question_type', $type)
            ->where('week', $week)
            ->when($subjectId, function ($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->get();

        $assigns = $type === "objective" ?
            AssignmentResource::collection($assign) :
            TheoryResource::collection($assign);

        return $this->success($assigns, 'List');
    }

    public function objectiveAnswer($request, $user): JsonResponse
    {
        if ($user->designation_id === 3) {
            return $this->error(null, 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {
            AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Submitted Successfully', 201);
    }

    public function theoryAnswer($request, $user): JsonResponse
    {
        if ($user->designation_id === 3) {
            return $this->error(null, 'Unauthenticated', 401);
        }

        $data = $request->json()->all();

        foreach ($data as $item) {
            AssignmentAnswer::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $user->id,
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "not marked",
                'submitted' =>  $item['submitted'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Submitted Successfully', 201);
    }

    public function getAnswer($request, $user): JsonResponse
    {
        $assign = AssignmentAnswer::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->where('question_type', $request->type)
            ->where('week', $request->week)
            ->get();

        if($request->type === "objective"){
            $assigns = AssignmentAnswerResource::collection($assign);
        }elseif($request->type === "theory"){
            $assigns = AssignmentAnswerResource::collection($assign);
        }else{
            $assigns = [];
        }

        return $this->success($assigns, 'List');
    }

    public function objectiveMark($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            AssignmentMark::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Marked Successfully', 201);
    }

    public function updateObjectiveMark($request): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = AssignmentMark::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, 'Assignment Mark not found', 400);
            }

            $assign->update([
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Assignment Mark updated successfully');
    }

    public function theoryMark($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            AssignmentMark::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Theory mark created successfully', 201);
    }

    public function updateTheoryMark($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = AssignmentMark::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, 'Theory mark not found', 400);
            }

            $assign->update([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $item['period'],
                'term' => $item['term'],
                'session' => $item['session'],
                'assignment_id' => $item['assignment_id'],
                'student_id' => $item['student_id'],
                'subject_id' => $item['subject_id'],
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_type' => $item['question_type'],
                'answer' =>  $item['answer'],
                'correct_answer' =>  $item['correct_answer'],
                'mark' => "marked",
                'submitted' =>  $item['submitted'],
                'teacher_mark' =>  $item['teacher_mark'],
                'week' => $item['week']
            ]);
        }

        return $this->success(null, 'Theory mark updated successfully');
    }

    public function marked($request, $user): JsonResponse
    {
        $assign = AssignmentMark::with('assignment')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', operator: $request->session)
            ->where('question_type', $request->type)
            ->where('week', $request->week)
            ->get();

        if ($request->type === "objective") {
            $assigns = AssignmentMarkResource::collection($assign);
        } elseif ($request->type === "theory") {
            $assigns = AssignmentMarkResource::collection($assign);
        } else {
            $assigns = [];
        }

        return $this->success($assigns, 'List');
    }

    public function markedByStudent($request, $user): JsonResponse
    {
        $assign = AssignmentMark::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $request->student_id)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->where('question_type', $request->type)
            ->where('week', $request->week)
            ->get();

        if ($request->type === "objective") {
            $assigns = AssignmentMarkResource::collection($assign);
        } elseif ($request->type === "theory") {
            $assigns = AssignmentMarkResource::collection($assign);
        } else {
            $assigns = [];
        }

        return $this->success($assigns, 'List');
    }

    public function editObjectiveAssign($request): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = Assignment::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, 'Assignment not found', 400);
            }

            $assign->update([
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_mark' => $item['question_mark'],
                'answer' =>  $item['answer'],
                'option1' => $item['option1'],
                'option2' => $item['option2'],
                'option3' => $item['option3'],
                'option4' => $item['option4'],
                'status' => $item['status'],
            ]);
        }

        return $this->success(null, 'Assignment updated successfully');
    }

    public function editTheoryAssign($request): JsonResponse
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = Assignment::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, 'Assignment not found', 404);
            }

            $assign->update([
                'question' => $item['question'],
                'question_number' => $item['question_number'],
                'question_mark' => $item['question_mark'],
                'answer' => $item['answer'],
                'status' => $item['status']
            ]);
        }

        return $this->success(null, 'Updated Successfully');
    }

    public function delAssign($request): JsonResponse
    {
        $assignment = Assignment::where('id', $request->id)->first();

        if (! $assignment) {
            return $this->error(null, 'Assignment not found', 404);
        }

        $assignment->delete();

        return $this->success(null, 'Deleted Successfully');
    }

    public function result($request, $user): JsonResponse
    {
        $data = $request->json()->all();

        try {
            DB::beginTransaction();

            foreach ($data['result'] as $item) {
                AssignmentResult::updateOrCreate([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'period' => $item['period'],
                    'term' => $item['term'],
                    'session' => $item['session'],
                    'assignment_id' => $item['assignment_id'],
                    'student_id' => $item['student_id'],
                    'subject_id' => $item['subject_id'],
                    'question_type' => $item['question_type'],
                    'total_mark' => $item['total_mark'],
                    'score' => $item['score'],
                    'week' => $item['week']
                ]);
            }

            $performanceData = $data['performance'];
            AssignmentPerformance::updateOrCreate([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $performanceData['period'],
                'term' => $performanceData['term'],
                'session' => $performanceData['session'],
                'assignment_id' => $performanceData['assignment_id'],
                'student_id' => $performanceData['student_id'],
                'subject_id' => $performanceData['subject_id'],
                'question_type' => $performanceData['question_type'],
                'total_mark' => $performanceData['total_mark'],
                'percentage_score' => $performanceData['percentage_score'],
                'week' => $performanceData['week']
            ]);

            DB::commit();

            return $this->success(null, 'Submitted Successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error(null, $e->getMessage(), 400);
        }
    }

    public function resultAssign($request, $user): JsonResponse
    {
        $assign = AssignmentResult::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', operator: $request->session)
            ->where('question_type', $request->type)
            ->where('week', $request->week)
            ->get();

        $assigns = AssignmentResultResource::collection($assign);

        return $this->success($assigns, 'List');
    }

    public function getStudentResult($request, $user): JsonResponse
    {
        $assign = AssignmentResult::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $request->student_id)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', operator: $request->session)
            ->where('question_type', $request->type)
            ->get();

        $assigns = AssignmentResultResource::collection($assign);

        return $this->success($assigns, 'List');
    }

    public function publish($request, $user): JsonResponse
    {
        $assign = Assignment::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', operator: $request->session)
            ->where('question_type', $request->question_type)
            ->where('week', $request->week)
            ->get();

        if ($request->is_publish) {
            $assign->each(function ($assignment) {
                $assignment->update([
                    'status' => 'published'
                ]);
            });
        } else {
            $assign->each(function ($assignment) {
                $assignment->update([
                    'status' => 'unpublished'
                ]);
            });
        }

        return $this->success(null, "Updated successfully!");
    }
}
