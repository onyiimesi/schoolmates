<?php

namespace App\Traits;

use App\Enum\StudentStatus;
use App\Http\Resources\GradingSystemResource;
use App\Http\Resources\MidTermResultResource;
use App\Http\Resources\StudentResource;
use App\Http\Resources\SubjectClassResource;
use App\Models\ClassModel;
use App\Models\ExtraCurricular;
use App\Models\GradingSystem;
use App\Models\Result;
use App\Models\SchoolScoreSetting;
use App\Models\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

trait ResultTrait
{
    public function getStudentsByClass($user, $class)
    {
        $data = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('present_class', $class)
            ->where('status', StudentStatus::ACTIVE)
            ->get();

        return StudentResource::collection($data);
    }

    public function getSubjects($user, $request)
    {
        $data = ClassModel::with(['subjects' => function ($query) use($request) {
                $query->where('session', $request['session']);
            }])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request['class'])
            ->get();

        return SubjectClassResource::collection($data);
    }

    public function getGrading($user)
    {
        return GradingSystemResource::collection(
            GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );
    }

    public function getExtraCurricular($user)
    {
        return ExtraCurricular::select('id', 'name')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();
    }

    public function getScoreSetting($user)
    {
        return SchoolScoreSetting::with('scoreOption')
            ->byCampus($user)
            ->first();
    }

    protected function getAssessmentResults($user, $params, string $resultType)
    {
        return MidTermResultResource::collection(
            Result::with(['student', 'studentScores'])
                ->where([
                    'sch_id' => $user->sch_id,
                    'campus' => $user->campus,
                    'student_id' => $params['student_id'],
                    'term' => $params['term'],
                    'session' => $params['session'],
                    'result_type' => $resultType,
                ])
                ->get()
        );
    }

    protected function getResultsForStudent($request)
    {
        return Result::with('studentScores')
            ->where('student_id', $request->student_id)
            ->where('class_name', $request->class_name)
            ->where('session', $request->session)
            ->get();
    }

    protected function getAllResultsForClass($request)
    {
        return Result::with('studentScores')
            ->where('class_name', $request->class_name)
            ->where('session', $request->session)
            ->get();
    }

    protected function calculateTotalScore($results, $studentSubjectCount = false): array
    {
        $total = 0;
        $count = 0;

        foreach ($results as $result) {
            foreach ($result->studentScores as $score) {
                $total += $score->score;
                if ($studentSubjectCount && $result->period === "Second Half" && $score->score > 0) {
                    $count++;
                }
            }
        }

        return ['total' => $total, 'count' => $count];
    }

    protected function calculateAverage($total, $count)
    {
        return $count > 0 ? $total / $count : 0;
    }

    protected function getAssessmentTypes(int $assessmentType): array
    {
        return match ($assessmentType) {
            1 => ['midterm'],
            2 => ['first_assesment', 'second_assesment'],
            3 => ['first_assesment', 'second_assesment', 'third_assesment'],
            4 => ['first_assesment', 'second_assesment', 'third_assesment', 'fourth_assesment'],
            default => [],
        };
    }

    /**
     * Get the students results used in studentaverage function (EndTermResultController).
    */
    protected function getResult(User $user, $validated): Collection
    {
        return Result::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'student_id' => $validated['student_id'],
                'class_name' => $validated['class_name'],
                'term' => $validated['term'],
                'session' => $validated['session'],
            ])
            ->with('studentScores')
            ->get();
    }

    /**
     * Get the class results used in studentaverage function (EndTermResultController).
    */
    protected function getClassResult(User $user, $validated): Collection
    {
        return Result::with('student')->where([
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
    }

    /**
     * Get the class average used in studentaverage function (EndTermResultController).
    */
    protected function getClassAverage(Collection $classResults, int $studentCount): float
    {
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
        return ($totalSubjects > 0) ? $totalClassScores / $totalSubjects : 0;
    }

    /**
     * Get the student average used in studentaverage function (EndTermResultController).
    */
    protected function getStudentAverage(Collection $results): float
    {
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
        return $totalStudentSubjects > 0 ? $totalStudentScores / $totalStudentSubjects : 0;
    }
}



