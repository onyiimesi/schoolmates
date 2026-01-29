<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\ResultStatus;
use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Student;
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
            "status" => true,
            "Class Average" => $classAverage,
            "Student Average" => $studentAverage,
            "Grade" => $grade ? $grade->remark : 'No grade',
        ];
    }

    public function studentaverage(Request $request): array
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'term' => 'required|string',
            'session' => 'required|string',
            'class_name' => 'required|string',
        ]);

        $user = Auth::user();

        $results = $this->getResult($user, $validated);
        $classResults = $this->getClassResult($user, $validated);
        $studentCount = Student::studentCountByClass($user, $validated['class_name']);
        $classAverage = $this->getClassAverage($classResults, $studentCount);
        $studentAverage = $this->getStudentAverage($results);

        $grade = GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('score_to', '>=', $studentAverage)
            ->first();

        $grades = $studentAverage > 90 ? "EXCELLENT" : ($grade->remark ?? "");

        return [
            "status" => true,
            "Student Average" => $studentAverage,
            "Class Average" => number_format($classAverage, 2),
            "Grade" => $grades,
        ];
    }
}
