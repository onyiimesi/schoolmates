<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

        // STAFF counts in one query
        $staffCounts = Staff::select('gender', DB::raw('COUNT(*) as total'))
            ->where('sch_id', $user->sch_id)
            ->groupBy('gender')
            ->pluck('total', 'gender');

        // STUDENT counts in one query
        $studentCounts = Student::select('gender', DB::raw('COUNT(*) as total'))
            ->where('sch_id', $user->sch_id)
            ->groupBy('gender')
            ->pluck('total', 'gender');

        $staffMale   = $staffCounts->get('male', 0);
        $staffFemale = $staffCounts->get('female', 0);
        $staffTotal  = $staffMale + $staffFemale;

        $studentMale   = $studentCounts->get('male', 0);
        $studentFemale = $studentCounts->get('female', 0);
        $studentTotal  = $studentMale + $studentFemale;

        $data = [
            'staff' => [
                'male'   => $staffMale,
                'female' => $staffFemale,
                'total'  => $staffTotal
            ],
            'student' => [
                'male'   => $studentMale,
                'female' => $studentFemale,
                'total'  => $studentTotal
            ],
            'total_school_population' => $staffTotal + $studentTotal
        ];

        return $this->success($data, "School population");
    }

}
