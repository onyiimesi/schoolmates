<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WithdrawStudentController extends Controller
{
    use HttpResponses;

    public function acceptStudent(Student $student): JsonResponse
    {
        $student->update(['status' => 'active']);

        return $this->success(null, 'Student Accepted Successfully');
    }

    public function withdraw(Student $student): JsonResponse
    {
        $student->update([
            'status' => 'withdrawn',
        ]);

        return $this->success(null, 'Student Withdrawn Successfully');
    }
}
