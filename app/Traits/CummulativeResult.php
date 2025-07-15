<?php

namespace App\Traits;

use App\Models\GradingSystem;
use App\Models\Result;

trait CummulativeResult
{
    public function getResults($user, $request)
    {
        return Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('student_id', $request->student_id)
            ->where('session', $request->session)
            ->with('studentscore')
            ->get();
    }

    public function initializeSubjects($results)
    {
        $subjects = [];
        foreach ($results as $result) {
            foreach ($result->studentscore as $score) {
                $subject = $score->subject;
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
            }
        }
        return $subjects;
    }

    public function calculateStudentScores($results, &$subjects)
    {
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

                $this->updateSubjectScores($subjects[$subject], $term, $scoreValue);
            }

            if ($studentTotalSubjects > 0) {
                $studentAverage = $studentTotalScore / $studentTotalSubjects;
                $totalStudentsAverage += $studentAverage;
                $totalStudents++;
            }
        }

        return [
            'totalStudents' => $totalStudents,
            'totalStudentsAverage' => $totalStudentsAverage
        ];
    }

    public function updateSubjectScores(&$subject, $term, $scoreValue)
    {
        $subject['Highest'] = is_null($subject['Highest']) ? $scoreValue : max($subject['Highest'], $scoreValue);
        $subject['Lowest'] = is_null($subject['Lowest']) ? $scoreValue : min($subject['Lowest'], $scoreValue);
        $subject[$term] += $scoreValue;
        $subject['Total Score'] += $scoreValue;
    }

    public function calculateClassAverage($totalStudents, $totalStudentsAverage)
    {
        return $totalStudents > 0 ? $totalStudentsAverage / $totalStudents : 0;
    }

    public function finalizeSubjectData(&$subjects, $classAverage, $user, $request)
    {
        $subjectRanks = $this->calculateRanksPerSubject($user, $request);

        foreach ($subjects as $subjectName => &$subject) {
            $subject['Rank'] = $subjectRanks[$subjectName][$request->student_id] ?? null;
            $subject['Average Score'] = ($subject['Total Score'] > 0) ? $subject['Total Score'] / 3 : 0;
            $subject['Remark'] = $this->getRemark($subject, $user);
            $subject['Class Average'] = $classAverage;
        }
    }

    public function getRemark($subject, $user)
    {
        $scores = [
            $subject['First Term'],
            $subject['Second Term'],
            $subject['Third Term']
        ];

        $scoreToCheck = max($scores);
        $grades = GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('score_to', '>=', $scoreToCheck)
            ->get();

        return $grades->isNotEmpty() ? $grades->first()->remark : null;
    }

    public function calculateRanksPerSubject($user, $request)
    {
        $allResults = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('session', $request->session)
            ->with('studentscore')
            ->get();

        $subjectScores = [];

        foreach ($allResults as $result) {
            $studentId = $result->student_id;
            foreach ($result->studentscore as $score) {
                $subject = $score->subject;
                $scoreValue = $score->score;

                if (!isset($subjectScores[$subject])) {
                    $subjectScores[$subject] = [];
                }

                if (!isset($subjectScores[$subject][$studentId])) {
                    $subjectScores[$subject][$studentId] = 0;
                }

                $subjectScores[$subject][$studentId] += $scoreValue;
            }
        }

        $subjectRanks = [];
        foreach ($subjectScores as $subject => $students) {
            arsort($students);
            $rank = 1;
            foreach ($students as $studentId => $score) {
                $subjectRanks[$subject][$studentId] = $rank++;
            }
        }

        return $subjectRanks;
    }
}
