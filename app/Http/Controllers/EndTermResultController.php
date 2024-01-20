<?php

namespace App\Http\Controllers;

use App\Http\Resources\CummulativeScoreResource;
use App\Http\Resources\ResultResource;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EndTermResultController extends Controller
{
    public function endterm(Request $request){

        $user = Auth::user();

        $search = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("student_id", $request->student_id)
        ->where("period", 'Second Half')
        ->where("term", $request->term)
        ->where("session", $request->session)->get();

        $s = ResultResource::collection($search);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $s
        ];

    }

    public function cummulative(Request $request)
    {

        $user = Auth::user();

        $results = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('student_id', $request->student_id)
        ->where('period', $request->period)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $subjects = [];
        $totalStudents = 0;
        $totalStudentsAverage = 0;

        foreach ($results as $result) {
            $studentTotalScore = 0;
            $studentTotalSubjects = 0;
            foreach ($result->studentscore as $score) {
                $subject = $score->subject;
                $term = $result->term;
                $scoreValue = $score->score;

                $studentTotalScore += $scoreValue;
                $studentTotalSubjects++;


                if (!isset($subjects[$subject])) {
                    $subjects[$subject] = [
                        'subject' => $subject,
                        'First Term' => 0,
                        'Second Term' => 0,
                        'Third Term' => 0,
                        'Total Score' => 0,
                        'Average Score' => 0,
                        'Remark' => "",
                        'Rank' => null,
                        'Class Average' => null,
                        'Highest' => null,
                        'Lowest' => null
                    ];
                }

                if (is_null($subjects[$subject]['Highest']) || $scoreValue > $subjects[$subject]['Highest']) {
                    $subjects[$subject]['Highest'] = $scoreValue;
                }

                if (is_null($subjects[$subject]['Lowest']) || $scoreValue < $subjects[$subject]['Lowest']) {
                    $subjects[$subject]['Lowest'] = $scoreValue;
                }

                $subjects[$subject][$term] = $scoreValue;
                $subjects[$subject]['Total Score'] += $scoreValue;
            }

            if ($studentTotalSubjects > 0) {
                $studentAverage = $studentTotalScore / $studentTotalSubjects;
                $totalStudentsAverage += $studentAverage;
                $totalStudents++;
            }
        }

        $classAverage = $totalStudents > 0 ? $totalStudentsAverage / $totalStudents : 0;

        foreach ($subjects as &$subject) {
            $subject['Average Score'] = $subject['Total Score'] / 3;
        }

        $totalScores = array_column($subjects, null, 'subject');
        arsort($totalScores);

        $rank = 1;
        foreach ($totalScores as &$subject) {
            $subject['Rank'] = $rank++;
        }

        // $totalScoreSum = array_sum(array_column($totalScores, 'Total Score'));
        // $classAverage = $totalScoreSum / count($totalScores);

        foreach ($totalScores as &$subject) {
            $scores = [
                $subject['First Term'],
                $subject['Second Term'],
                $subject['Third Term']
            ];

            $scoreToCheck = max($scores);
            $grades = GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('score_to', '>=', $scoreToCheck)->get();
            $remark = null;

            if ($grades->isNotEmpty()) {
                $remark = $grades->first()->remark;
            }

            $subject['Remark'] = $remark;
            $subject['Class Average'] = $classAverage;

        }

        $displayData = array_values($totalScores);

        $resourceCollection = CummulativeScoreResource::collection(collect($displayData));

        return [
            'status' => 'true',
            'message' => '',
            'data' => $resourceCollection
        ];

    }

    public function endaverage(Request $request)
    {
        $user = Auth::user();

        $results = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('class_name', $request->class_name)
        ->where('session', $request->session)
        ->with('studentscore')
        ->get();

        $count = Result::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('class_name', $request->class_name)
        ->count();

        $totalScore = 0;

        $totalScores = 0;
        $totalSubject = 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScore += $score->score;
            }
        }

        $classAverage = $totalScore > 0 ? $totalScore / $count : 0;

        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $totalScores += $score->score;
                $totalSubject++;
            }
        }

        $studentAverage = $totalScores > 0 ? $totalScores / $totalSubject : 0;

        $grade = GradingSystem::where('score_to', '>=', $studentAverage)->first();

        return [
            "status" => "true",
            "Class Average" => $classAverage,
            "Student Average" => $studentAverage,
            "Grade" => $grade->remark
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
        if($studentAverage > 90){
            $grades = "EXCELLENT";
        }else{
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
