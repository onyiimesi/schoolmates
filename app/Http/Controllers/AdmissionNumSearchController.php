<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentResource;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdmissionNumSearchController extends Controller
{
    use HttpResponses;

    public function admissionsearch(Request $request): JsonResponse
    {
        $search = Student::where("admission_number", $request->admissionnumber)->get();

        $data = StudentResource::collection($search);

        return $this->success($data, 'Admission Number Search');
    }
}
