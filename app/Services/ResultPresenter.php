<?php

namespace App\Services;

use App\Enum\PeriodicName;
use App\Enum\StaffStatus;
use App\Models\Result;
use App\Models\StudentScore;
use App\Models\GradingSystem;
use App\Models\ClassModel;
use App\Models\Schools;
use App\Models\Staff;

class ResultPresenter
{
    public function getGpa(Result $result): array
    {
        $studentResults = Result::with(['studentScores' => function ($query) {
            $query->where('score', '>', 0);
        }])
        ->where([
            'sch_id' => $result->sch_id,
            'campus' => $result->campus,
            'student_id' => $result->student_id,
            'class_name' => $result->class_name,
            'term' => $result->term,
            'session' => $result->session,
        ])->get();

        $scores = $studentResults->pluck('studentScores')->flatten();
        $totalScore = $scores->sum('score');
        $totalSubjects = $scores->unique('subject')->count();

        $expectedScore = $totalSubjects * 100;
        $gpa = ($expectedScore > 0) ? round(($totalScore / $expectedScore) * 5, 2) : 0;

        return [
            'total_score' => $totalScore,
            'total_subjects' => $totalSubjects,
            'student_average' => $totalSubjects > 0 ? round($totalScore / $totalSubjects, 2) : 0,
            'gpa' => $gpa
        ];
    }

    public function getClassStats(Result $result): array
    {
        $classResults = Result::with(['studentScores' => function ($query) {
                $query->where('score', '>', 0);
            }])
            ->where('sch_id', $result->sch_id)
            ->where('campus', $result->campus)
            ->where('class_name', $result->class_name)
            ->where('term', $result->term)
            ->where('session', $result->session)
            ->get();

        $studentAverages = $classResults->map(function ($r) {
            $scores = $r->studentScores?->pluck('score') ?? collect();
            $totalSubjects = $scores->count();
            return [
                'student_id' => $r->student_id,
                'average' => $totalSubjects > 0 ? $scores->sum() / $totalSubjects : 0,
            ];
        })->sortByDesc('average')->values();

        $position = $studentAverages->search(fn($r) => $r['student_id'] === $result->student_id) + 1;

        // Calculate the sum of all student averages
        $totalStudentAverages = $studentAverages->sum('average');

        $scores = $classResults->flatMap->studentScores;
        $classTotalScore = $scores->sum('score');
        $classCount = $classResults->pluck('student_id')->unique()->count();

        if (in_array($result->term, ['First Term', 'Second Term'])) {
            $subjectCount = $scores->unique('subject')->count();
            $classAverage = ($classCount > 0 && $subjectCount > 0)
                ? round($classTotalScore / ($classCount * $subjectCount), 2)
                : 0;
        } else {
            $classAverage = $classCount > 0 ? round($totalStudentAverages / $classCount, 2) : 0;
        }

        $classGrade = GradingSystem::where('sch_id', $result->sch_id)
            ->where('campus', $result->campus)
            ->where('score_to', '>=', $classAverage)
            ->first();

        $grade = $classAverage > 90 ? 'EXCELLENT' : ($classGrade->remark ?? '');

        return [
            'class_total_score' => $classTotalScore,
            'class_count' => $classCount,
            'class_average' => $classAverage,
            'class_grade' => $grade,
            'position' => $position,
        ];
    }

    public function getMetadata(Result $result, string $className): array
    {
        $class = ClassModel::where([
            'sch_id' => $result->sch_id,
            'campus' => $result->campus,
            'class_name' => $className
        ])->first();

        $staff = Staff::where([
            'sch_id' => $result->sch_id,
            'campus' => $result->campus,
            'class_assigned' => $className,
            'status' => StaffStatus::ACTIVE,
        ])->get();

        $hodQuery = Staff::where('sch_id', $result->sch_id)
            ->where('campus', $result->campus)
            ->where('id', $result->hos_id)
            ->where('designation_id', 3)
            ->where('status', StaffStatus::ACTIVE)
            ->when($class && $class->class_type !== null, fn($q) => $q->where('class_type', $class->class_type));

        $dos = Schools::where('sch_id', $result->sch_id)->value('dos');

        return [
            'staff' => $staff,
            'hods' => $hodQuery->get(),
            'dos' => $dos
        ];
    }

    public function getSubjectAverages(Result $result): array
    {
        return StudentScore::query()
            ->whereHas('result', fn ($q) => $q->where([
                'sch_id'     => $result->sch_id,
                'campus'     => $result->campus,
                'class_name' => $result->class_name,
                'term'       => $result->term,
                'session'    => $result->session,
            ]))
            ->whereNotNull('score')
            ->selectRaw('subject, ROUND(AVG(score), 2) as avg_score')
            ->groupBy('subject')
            ->pluck('avg_score', 'subject')
            ->map(fn ($v) => (float) $v)
            ->toArray();
    }

    public function getSubjectPositions(Result $result): array
    {
        $studentScores = $result->studentScores ?? collect();
        $positions = [];

        foreach ($studentScores as $score) {
            $subject = $score->subject;

            $allScores = StudentScore::with('result')
                ->where('subject', $subject)
                ->whereHas('result', function ($query) use ($result) {
                    $query->where([
                        'sch_id' => $result->sch_id,
                        'campus' => $result->campus,
                        'class_name' => $result->class_name,
                        'period' => PeriodicName::SECONDHALF,
                        'term' => $result->term,
                        'session' => $result->session,
                    ]);
                })
                ->orderByDesc('score')
                ->get();

            // Compute rankings with tie handling
            $rank = 1;
            $positionMap = [];
            $prevScore = null;
            $tieCount = 0;

            foreach ($allScores as $index => $entry) {
                $entryStudentId = $entry->result?->student_id;

                if ($entry->score !== $prevScore) {
                    $rank += $tieCount;
                    $tieCount = 1;
                    $positionMap[$entryStudentId] = $rank;
                } else {
                    $tieCount++;
                    $positionMap[$entryStudentId] = $rank;
                }

                $prevScore = $entry->score;
                logger()->info("Index: $index");
            }

            $positions[$subject] = $positionMap[$result->student_id] ?? null;
        }

        return $positions;
    }
}
