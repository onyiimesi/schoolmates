<?php

namespace App\Http\Controllers;

use App\Enum\StaffStatus;
use App\Enum\StudentStatus;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ClassPopulationController extends Controller
{
    use HttpResponses;

    public function getClassPopulation()
    {
        $staff = Auth::user();

        $class = Student::where('present_class', $staff->class_assigned)
            ->where('sch_id', $staff->sch_id)
            ->where('campus', $staff->campus)
            ->count();

        return $this->success($class, "Class population");
    }

    public function getStudentPopulation()
    {
        $staff = Auth::user();
        $studentCount = Student::where('sch_id', $staff->sch_id)->count();

        return $this->success($studentCount, "Total Student Population");
    }

    public function getStaffPopulation()
    {
        $staff = Auth::user();
        $staffCount = Staff::where('sch_id', $staff->sch_id)->count();

        return $this->success($staffCount, "Total Staff Population");
    }

    public function getTeacherPopulation()
    {
        $staff = Auth::user();

        $totalCount = Staff::where('designation_id', '4')
            ->where('sch_id', $staff->sch_id)
            ->count();

        return $this->success($totalCount, "Total Teacher Population");
    }

    public function getSchoolPopulation()
    {
        $user = Auth::user();
        $cacheKey = "school_population_{$user->sch_id}";

        $data = Cache::remember($cacheKey, now()->addHour(), function () use ($user) {

            $staffCounts = Staff::select('gender', DB::raw('COUNT(*) as total'))
                ->where('sch_id', $user->sch_id)
                ->where('status', StaffStatus::ACTIVE)
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $staffTotal  = Staff::where('sch_id', $user->sch_id)
                ->where('status', StaffStatus::ACTIVE)
                ->count();

            $studentCounts = Student::select('gender', DB::raw('COUNT(*) as total'))
                ->where('sch_id', $user->sch_id)
                ->where('status', StudentStatus::ACTIVE)
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $studentTotal  = Student::where('sch_id', $user->sch_id)
                ->where('status', StudentStatus::ACTIVE)
                ->count();

            $teacherCounts = Staff::select('gender', DB::raw('COUNT(*) as total'))
                ->where('sch_id', $user->sch_id)
                ->where('status', StaffStatus::ACTIVE)
                ->where('designation_id', '4')
                ->groupBy('gender')
                ->pluck('total', 'gender');

            $teacherTotal = Staff::where('sch_id', $user->sch_id)
                ->where('status', StaffStatus::ACTIVE)
                ->where('designation_id', '4')
                ->count();

            $getCount = fn($counts, $key) => (int)($counts[$key] ?? 0);

            return [
                'staffs' => [
                    'male'   => $getCount($staffCounts, 'male'),
                    'female' => $getCount($staffCounts, 'female'),
                    'total'  => $staffTotal
                ],
                'students' => [
                    'male'   => $getCount($studentCounts, 'male'),
                    'female' => $getCount($studentCounts, 'female'),
                    'total'  => $studentTotal
                ],
                'teachers' => [
                    'male'   => $getCount($teacherCounts, 'male'),
                    'female' => $getCount($teacherCounts, 'female'),
                    'total'  => $teacherTotal
                ],
                'total_school_population' => $staffTotal + $studentTotal
            ];
        });

        return $this->success($data, "School population");
    }
}
