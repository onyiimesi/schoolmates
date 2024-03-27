<?php

namespace App\Http\Controllers;

use App\Models\AssignmentMark;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignmentPerformanceController extends Controller
{
    use HttpResponses;

    public function chart(Request $request)
    {
        $user = Auth::user();

        $period = $request->input('period');
        $term = $request->input('term');
        $session = $request->input('session');
        $studentId = $request->input('student_id');
        $subjectId = $request->input('subject_id');
        $type = $request->input('type');

        $query = DB::table('assignment_marks')
            ->select('student_id', 'week', DB::raw('SUM(teacher_mark) as total_score'))
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('subject_id', $subjectId)
            ->where('question_type', $type);

        if ($studentId) {
            $query->where('student_id', $studentId);
        }

        $assignments = $query->groupBy('student_id', 'week')
            ->orderBy('student_id')
            ->orderBy('week')
            ->get();

        $studentsData = [];
        foreach ($assignments as $assignment) {
            $studentId = $assignment->student_id;
            $totalScore = $assignment->total_score;
            $percentageScore = $totalScore / ($assignment->week * 100);

            $studentData = [
                'student_id' => $studentId,
                'week' => $assignment->week,
                'total_score' => $totalScore,
                'percentage_score' => $percentageScore,
            ];

            $studentsData[] = $studentData;
        }

        $data[] = [
            'period' => $period,
            'term' => $term,
            'session' => $session,
            'subject_id' => $subjectId,
            'type' => $type,
            'students' => $studentsData
        ];

        return $this->success($data, "Performance Chart", 200);
    }
}
