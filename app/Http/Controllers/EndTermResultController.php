<?php

namespace App\Http\Controllers;

use App\Enum\ResultStatus;
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
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'period' => 'First Half',
                'term' => $request->term,
                'session' => $request->session,
            ])
            ->get();

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

        // Fetch results for the specific student
        $results = Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $request->student_id,
                'class_name' => $request->class_name,
                'term' => $request->term,
                'session' => $request->session,
                'status' => ResultStatus::RELEASED,
            ])
            ->with('studentscore')
            ->get();

        // Fetch results for the entire class
        $classResults = Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'class_name' => $request->class_name,
                'term' => $request->term,
                'session' => $request->session,
                'status' => ResultStatus::RELEASED,
            ])
            ->with('studentscore')
            ->get();

        // Count the number of students in the class
        $studentCount = Student::where('present_class', $request->class_name)->count();

        // Calculate total scores for the class
        $totalClassScores = 0;
        $allSubjects = [];

        foreach ($classResults as $result) {
            foreach ($result->studentscore as $score) {
                if ($score->score != 0) {
                    $totalClassScores += $score->score;
                    $allSubjects[] = $score->subject;
                }
            }
        }

        // Calculate the total number of subjects in the class (unique subjects)
        $totalSubjects = count(array_unique($allSubjects)) * $studentCount;

        // Calculate the class average
        $classAverage = ($totalSubjects > 0) ? $totalClassScores / $totalSubjects : 0;

        // Calculate total scores and unique subjects for the specific student
        $totalStudentScores = 0;
        $uniqueStudentSubjects = [];

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                if ($score->score != 0) {
                    $totalStudentScores += $score->score;
                    $uniqueStudentSubjects[] = $score->subject;
                }
            }
        }

        $totalStudentSubjects = count(array_unique($uniqueStudentSubjects));
        $studentAverage = $totalStudentSubjects > 0 ? $totalStudentScores / $totalStudentSubjects : 0;

        // Determine the grade for the student
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

}
