<?php

namespace App\Http\Controllers;

use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Student;
use App\Traits\CummulativeResult;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EndTermResultController extends Controller
{
    use CummulativeResult, HttpResponses;

    public function endterm(Request $request)
    {
        $user = Auth::user();

        $search = Result::with([
            'studentscore',
            'affectivedisposition',
            'psychomotorskill',
            'resultextracurricular',
            'abacus',
            'psychomotorperformance',
            'pupilreport',
        ])
        ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'Second Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->get();

        $data = ResultResource::collection($search);

        return $this->success($data, 'End term result');
    }

    public function cummulative(Request $request)
    {
        $user = Auth::user();
        $results = $this->getResults($user, $request);
        $subjects = $this->initializeSubjects($results);
        $totalStudentsData = $this->calculateStudentScores($results, $subjects);

        $classAverage = $this->calculateClassAverage($totalStudentsData['totalStudents'], $totalStudentsData['totalStudentsAverage']);
        $this->finalizeSubjectData($subjects, $classAverage, $user);

        $resourceCollection = CummulativeScoreResource::collection(collect(array_values($subjects)));

        return [
            'status' => 'true',
            'message' => '',
            'data' => $resourceCollection
        ];
    }

    public function endaverage(Request $request)
    {
        $results = Result::with(['studentscore'])
        ->where('student_id', $request->student_id)
        ->where('class_name', $request->class_name)
        ->where('session', $request->session)
        ->get();

        $allResults = Result::with(['studentscore'])
        ->where('class_name', $request->class_name)
        ->where('session', $request->session)
        ->get();

        $totalStudentScores = 0;
        $totalStudentSubjectCount = 0;
        $totalScores = 0;
        $totalSubjectCount = 0;

        // Calculate total scores and total number of subjects for student average for all students
        foreach ($allResults as $result) {
            foreach ($result->studentscore as $score) {
                $totalStudentScores += $score->score;
                if ($result->period === "Second Half" && $score->score > 0) {
                    $totalStudentSubjectCount++;
                }
            }
        }

        // Calculate total scores and total number of subjects for student average for a student
        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScores += $score->score;
                if ($result->period === "Second Half" && $score->score > 0) {
                    $totalSubjectCount++;
                }
            }
        }

        // Calculate student average
        $studentAverage = 0;
        if ($totalSubjectCount > 0) {
            $studentAverage = $totalScores / $totalSubjectCount;
        }

        // Calculate class average
        $classAverage = 0;
        if ($totalScores > 0) {
            $classAverage = $totalStudentScores / $totalScores;
        }

        $grade = GradingSystem::where('score_to', '>=', $studentAverage)->first();

        return [
            "status" => "true",
            "Class Average" => $classAverage,
            "Student Average" => $studentAverage,
            "Grade" => $grade ? $grade->remark : 'No grade'
        ];
    }

    public function studentaverage(Request $request)
    {
        $user = Auth::user();

        $results = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $request->student_id)
            ->where('class_name', $request->class_name)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->with('studentscore')
            ->get();

        $res = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class_name)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->with('studentscore')
            ->get();
        $count = Student::where('present_class', $request->class_name)->count();

        $totalScores = 0;
        $uniqueSubject = [];
        foreach ($res as $result) {
            foreach ($result->studentscore as $score) {
                if ($score->score != 0) {
                    $totalScores += $score->score;
                    $uniqueSubject[] = $score->subject;
                }
            }
        }
        $totalSubjects = count(array_unique($uniqueSubject));
        $studentAverages = $totalSubjects > 0 ? $totalScores / $totalSubjects : 0;
        $classAverage = $studentAverages / $count;

        $totalScore = 0;
        $uniqueSubjects = [];
        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                if ($score->score != 0) {
                    $totalScore += $score->score;
                    $uniqueSubjects[] = $score->subject;
                }
            }
        }

        $totalSubject = count(array_unique($uniqueSubjects));
        $studentAverage = $totalSubject > 0 ? $totalScore / $totalSubject : 0;
        $grade = GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('score_to', '>=', $studentAverage)->first();
        if ($studentAverage > 90) {
            $grades = "EXCELLENT";
        } else {
            $grades = $grade->remark ?? "";
        }

        return [
            "status" => "true",
            "Student Average" => $studentAverage,
            "Class Average" => number_format($classAverage, 2),
            "Grade" => $grades,
        ];
    }
}
