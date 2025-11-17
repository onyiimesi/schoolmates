<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignmentResultRequest;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;

class AssignmentController extends Controller
{
    use HttpResponses;

    public const SUCCESS = 'Submitted Successfully';
    public const UPDATE = 'Updated Successfully';
    public const ASSIGNMENT_ERROR = 'Assignment does not exist';
    public const ASSIGNMENT_MARK_ERROR = 'Does not exist';

    public function objective(Request $request)
    {
        $user = Auth::user();
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

        return $this->success(null, 'Created Successfully');
    }

    public function theory(Request $request)
    {
        $user = Auth::user();
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

    public function assign(Request $request)
    {
        $user = Auth::user();

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

    public function objectiveAnswer(Request $request)
    {
        $user = Auth::user();

        if($user->designation_id === 3){
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

        return $this->success(null, SELF::SUCCESS);
    }

    public function theoryAnswer(Request $request)
    {
        $user = Auth::user();

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

        return $this->success(null, SELF::SUCCESS, 200);
    }

    public function getAnswer(Request $request)
    {
        $user = Auth::user();

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

    public function objectiveMark(Request $request)
    {
        $user = Auth::user();
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

        return $this->success(null, SELF::SUCCESS);
    }

    public function updateObjectiveMark(Request $request)
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = AssignmentMark::where('id', $item['id'])->first();

            if(! $assign) {
                return $this->error(null, SELF::ASSIGNMENT_MARK_ERROR, 400);
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

        return $this->success(null, SELF::UPDATE);
    }

    public function theoryMark(Request $request)
    {
        $user = Auth::user();
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

        return $this->success(null, SELF::SUCCESS, 200);
    }

    public function updateTheoryMark(Request $request)
    {
        $user = Auth::user();
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = AssignmentMark::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, SELF::ASSIGNMENT_MARK_ERROR, 400);
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

        return $this->success(null, SELF::UPDATE);
    }

    public function marked(Request $request)
    {
        $user = Auth::user();

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

    public function markedByStudent(Request $request)
    {
        $user = Auth::user();

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

    public function editObjectiveAssign(Request $request)
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = Assignment::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, SELF::ASSIGNMENT_ERROR, 400);
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

        return $this->success(null, 'Updated Successfully');
    }

    public function editTheoryAssign(Request $request)
    {
        $data = $request->json()->all();

        foreach ($data as $item) {
            $assign = Assignment::where('id', $item['id'])->first();

            if (! $assign) {
                return $this->error(null, SELF::ASSIGNMENT_ERROR, 400);
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

    public function delAssign(Request $request)
    {
        $assignment = Assignment::where('id', $request->id)->first();

        if (! $assignment) {
            return $this->error(null, SELF::ASSIGNMENT_ERROR, 400);
        }

        $assignment->delete();

        return response(null, 204);
    }

    public function result (AssignmentResultRequest $request)
    {
        $request->validated();

        $user = Auth::user();
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

    public function resultAssign(Request $request)
    {
        $user = Auth::user();

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

    public function getStudentResult(Request $request)
    {
        $user = Auth::user();

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

    public function publish(Request $request)
    {
        $request->validate([
            'period' => ['required', 'string'],
            'term' => ['required', 'string'],
            'session' => ['required', 'string'],
            'question_type' => ['required', 'string'],
            'week' => ['required', 'string'],
            'is_publish' => ['required', 'boolean', 'in:0,1']
        ]);

        $user = Auth::user();

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
