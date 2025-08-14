<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class ClassPopulationController extends Controller
{
    use HttpResponses;

    public function getclasspopulation()
    {
        $staff = Auth::user();

        $class = Student::where('present_class', $staff->class_assigned)
            ->where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->count();

        return $this->success($class, "Class population");
    }

    public function getallpopulation()
    {
        $staff = Auth::user();
        $studentCount = Student::where('sch_id', $staff->sch_id)->count();

        return $this->success($studentCount, "Total Student Population");
    }

    public function getstaffpopulation()
    {
        $staff = Auth::user();

        $staffCount = Staff::where('sch_id', $staff->sch_id)->count();

        return $this->success($staffCount, "Total Staff Population");
    }

    public function getteacherpopulation()
    {
        $staff = Auth::user();

        $totalCount = Staff::where('designation_id', '4')
            ->where('sch_id', $staff->sch_id)
            ->count();

        return $this->success($totalCount, "Total Teacher Population");
    }

    public function getschoolpopulation()
    {
        $user = Auth::user();

        $staff = Staff::where('sch_id', $user->sch_id)
        ->get();

        $student = Student::where('sch_id', $user->sch_id)
        ->get();

        $total = $staff->count() + $student->count();

        $data = [
            'staff' => [
                'total' => $staff->count()
            ],
            'student' => [
                'male' => $student->where('gender', 'male')->count(),
                'female' => $student->where('gender', 'female')->count(),
                'total' => $student->count(),
            ],
            'total_school_population' => $total
        ];

        return $this->success($data, "School population");
    }
}
