<?php

namespace App\Traits;

use App\Enum\StudentStatus;
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
            ->with('studentScores')
            ->get();
    }

    public function initializeSubjects($results)
    {
        $subjects = [];
        foreach ($results as $result) {
            foreach ($result->studentScores as $score) {
                $subjectKey = $this->normalizeSubject($score->subject);
                if (!isset($subjects[$subjectKey])) {
                    $subjects[$subjectKey] = [
                        'subject' => $score->subject,
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

            foreach ($result->studentScores as $score) {
                $subjectKey = $this->normalizeSubject($score->subject);
                $term = $result->term;
                $scoreValue = $score->score;

                if ($term && isset($subjects[$subjectKey])) {
                    $studentTotalScore += $scoreValue;
                    $studentTotalSubjects++;
                    $this->updateSubjectScores($subjects[$subjectKey], $term, $scoreValue);
                }
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

    public function finalizeSubjectData(&$subjects, $classAverage, $user, $request, $student)
    {
        $subjectRanks = $this->calculateRanksPerSubject($user, $request, $student);

        foreach ($subjects as $subjectName => &$subject) {
            $subjectKey = $this->normalizeSubject($subjectName);
            $subject['Rank'] = $subjectRanks[$subjectKey][$request->student_id] ?? null;
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

    public function calculateRanksPerSubject($user, $request, $student)
    {
        $allResults = Result::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('session', $request->session)
            ->where('class_name', $student->present_class)
            ->whereHas('student', function($q) {
                $q->where('status', StudentStatus::ACTIVE);
            })
            ->with('studentScores')
            ->get();

        $subjectScores = [];

        foreach ($allResults as $result) {
            $studentId = $result->student_id;
            foreach ($result->studentScores as $score) {
                $subjectKey = $this->normalizeSubject($score->subject);
                $scoreValue = $score->score;

                if (!isset($subjectScores[$subjectKey])) {
                    $subjectScores[$subjectKey] = [];
                }

                if (!isset($subjectScores[$subjectKey][$studentId])) {
                    $subjectScores[$subjectKey][$studentId] = 0;
                }

                $subjectScores[$subjectKey][$studentId] += $scoreValue;
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

    public function normalizeSubject($subject)
    {
        return strtoupper(trim($subject));
    }
}
