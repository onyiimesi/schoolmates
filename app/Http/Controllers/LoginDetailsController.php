<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffLoginDetailsResource;
use App\Http\Resources\StudentLoginDetailsResource;
use App\Models\Staff;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginDetailsController extends Controller
{
    use HttpResponses;

    public function loginDetails(Request $request)
    {
        $user = Auth::user();
        $search = $request->query('search');

        $students = Student::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->when($search, fn($query) =>
                $query->whereAny(
                    ['firstname', 'surname', 'email_address', 'username', 'admission_number', 'present_class'],
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(25);

        $details = StudentLoginDetailsResource::collection($students);

        return $this->withPagination($details, 'Student Login Details');
    }

    public function staffloginDetails(Request $request)
    {
        $user = Auth::user();
        $excludedDesignations = ['1', '2', '6'];
        $search = $request->query('search');

        $staff = Staff::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->whereNotIn('designation_id', $excludedDesignations)
            ->when($search, fn($query) =>
                $query->whereAny(
                    ['firstname', 'surname', 'email', 'username', 'class_assigned'],
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(25);

        $result = StaffLoginDetailsResource::collection($staff);

        return $this->withPagination($result, 'Staff Login Details');
    }
}
