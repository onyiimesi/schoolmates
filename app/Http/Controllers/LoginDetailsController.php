<?php

namespace App\Http\Controllers;

use App\Http\Resources\StaffLoginDetailsResource;
use App\Http\Resources\StudentLoginDetailsResource;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginDetailsController extends Controller
{
    public function loginDetails(){

        $user = Auth::user();

        $students = Student::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->paginate(25);

        $details = StudentLoginDetailsResource::collection($students);

        return [
            'status' => 'true',
            'message' => 'Student Login Details',
            'data' => $details,
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'prev_page_url' => $students->previousPageUrl(),
                'next_page_url' => $students->nextPageUrl(),
            ],
        ];

    }

    public function staffloginDetails(){

        $user = Auth::user();
        $excludedDesignations = ['1', '2', '6'];
        $staff = Staff::whereNotIn('designation_id', $excludedDesignations)
        ->where('campus', $user->campus)
        ->paginate(25);

        $sdetails = StaffLoginDetailsResource::collection($staff);



        return [
            'status' => 'true',
            'message' => 'Staff Login Details',
            'data' => $sdetails,
            'pagination' => [
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'per_page' => $staff->perPage(),
                'prev_page_url' => $staff->previousPageUrl(),
                'next_page_url' => $staff->nextPageUrl()
            ],
        ];

    }
}
