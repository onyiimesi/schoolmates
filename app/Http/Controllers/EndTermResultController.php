<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\ResultStatus;
use App\Http\Requests\GetResultRequest;
use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Student;
use App\Services\Cache\MemoizedCacheService;
use App\Traits\CummulativeResult;
use App\Traits\HttpResponses;
use App\Traits\ResultTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EndTermResultController extends Controller
{
    use CummulativeResult, HttpResponses, ResultTrait;

    public function endterm(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|string',
            'session' => 'required|string',
        ]);

        $user = Auth::user();

        $search = Result::with([
            'student',
            'studentScores',
            'affectiveDispositions',
            'psychomotorskill',
            'resultExtraCurriculars',
            'abacus',
            'psychomotorPerformances',
            'pupilReports',
        ])
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $validated['student_id'],
                'period' => PeriodicName::SECONDHALF,
                'term' => $validated['term'],
                'session' => $validated['session'],
                'status' => ResultStatus::RELEASED
            ])
            ->get();

        $data = ResultResource::collection($search);

        return $this->success($data, 'End term result');
    }

    public function staffEndTerm(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|string',
            'session' => 'required|string',
        ]);

        $user = Auth::user();

        $search = Result::with([
            'studentScores',
            'affectiveDispositions',
            'psychomotorskill',
            'resultExtraCurriculars',
            'abacus',
            'psychomotorPerformances',
            'pupilReports',
        ])
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $validated['student_id'],
                'period' => PeriodicName::SECONDHALF,
                'term' => $validated['term'],
                'session' => $validated['session']
            ])
            ->get();

        $data = ResultResource::collection($search);

        return $this->success($data, 'End term result');
    }

    public function cummulative(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $request->student_id)
            ->firstOrFail();

        $results = $this->getResults($user, $request);
        $subjects = $this->initializeSubjects($results);
        $totalStudentsData = $this->calculateStudentScores($results, $subjects);

        $classAverage = $this->calculateClassAverage($totalStudentsData['totalStudents'], $totalStudentsData['totalStudentsAverage']);
        $this->finalizeSubjectData($subjects, $classAverage, $user, $request, $student);

        $resourceCollection = CummulativeScoreResource::collection(collect(array_values($subjects)));

        return [
            'status' => 'true',
            'message' => '',
            'data' => $resourceCollection
        ];
    }

    public function endaverage(Request $request)
    {
        $studentResults = $this->getResultsForStudent($request);
        $classResults = $this->getAllResultsForClass($request);

        $classTotals = $this->calculateTotalScore($classResults, true);
        $studentTotals = $this->calculateTotalScore($studentResults, true);

        $studentAverage = $this->calculateAverage($studentTotals['total'], $studentTotals['count']);
        $classAverage = $studentTotals['total'] > 0
            ? $classTotals['total'] / $studentTotals['total']
            : 0;

        $grade = GradingSystem::where('score_to', '>=', $studentAverage)->first();

        return [
            "status" => "true",
            "Class Average" => $classAverage,
            "Student Average" => $studentAverage,
            "Grade" => $grade ? $grade->remark : 'No grade',
        ];
    }

    public function studentaverage(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|string',
            'session' => 'required|string',
            'class_name' => 'required|string',
        ]);

        $user = Auth::user();

        $results = Result::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'student_id' => $validated['student_id'],
            'class_name' => $validated['class_name'],
            'term' => $validated['term'],
            'session' => $validated['session'],
        ])
            ->with('studentScores')
            ->get();

        $classResults = Result::with('student')->where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'class_name' => $validated['class_name'],
            'term' => $validated['term'],
            'session' => $validated['session'],
        ])
            ->whereHas('student', function ($query) {
                $query->where('status', 'active');
            })
            ->with('studentScores')
            ->get();

        $studentCount = Student::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'present_class' => $validated['class_name'],
        ])
            ->count();

        $totalClassScores = 0;
        $allSubjects = [];

        foreach ($classResults as $result) {
            $subjectScores = [];

            foreach ($result->studentScores as $score) {
                if (!array_key_exists($score->subject, $subjectScores)) {
                    $subjectScores[$score->subject] = $score->score;
                }
            }

            foreach ($subjectScores as $subject => $subjectScore) {
                $totalClassScores += $subjectScore;
                $allSubjects[] = $subject;
            }
        }

        $totalSubjects = count(array_unique($allSubjects)) * $studentCount;
        $classAverage = ($totalSubjects > 0) ? $totalClassScores / $totalSubjects : 0;

        $totalStudentScores = 0;
        $uniqueStudentSubjects = [];

        foreach ($results as $result) {
            foreach ($result->studentScores as $score) {
                if ($score->score != 0) {
                    $totalStudentScores += $score->score;
                    $uniqueStudentSubjects[] = $score->subject;
                }
            }
        }

        $totalStudentSubjects = count(array_unique($uniqueStudentSubjects));
        $studentAverage = $totalStudentSubjects > 0 ? $totalStudentScores / $totalStudentSubjects : 0;

        $grade = GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('score_to', '>=', $studentAverage)
            ->first();

        $grades = $studentAverage > 90 ? "EXCELLENT" : ($grade->remark ?? "");

        return [
            "status" => "true",
            "Student Average" => $studentAverage,
            "Class Average" => number_format($classAverage, 2),
            "Grade" => $grades,
        ];
    }

    public function getResult(GetResultRequest $request, MemoizedCacheService $generalResultService)
    {
        $user = userAuth();

        $validated = $request->validated();

        return $this->getStudentResults($user, $validated, $generalResultService);
    }

    private function getStudentResults($user, array $params, MemoizedCacheService $generalResultService)
    {
        return match ($params['period']) {
            PeriodicName::FIRSTHALF => $generalResultService->firstHalf($user, $params),
            PeriodicName::SECONDHALF => $generalResultService->secondHalf($user, $params),
            default => $this->error(null, 'Invalid result type', 400),
        };
    }
}
