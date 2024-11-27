<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReleaseResultRequest;
use App\Http\Requests\ResultRequest;
use App\Models\Result;
use App\Models\Staff;
use App\Traits\HttpResponses;
use App\Traits\ResultBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResultTwoController extends Controller
{
    use HttpResponses, ResultBase;

    public function mid(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'string'],
            'student_fullname' => ['required', 'string'],
            'admission_number' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'session' => ['required', 'string', 'max:255'],
            'result_type' => 'required|in:first_assesment,second_assesment,midterm'
        ]);

        $teacher = Auth::user();

        if ($request->period === "First Half") {
            $getResult = $this->getResult($teacher, $request);

            if (empty($getResult)) {
                return $this->createResult($teacher, $request);
            } else {
                return $this->updateResult($getResult, $request);
            }
        }

        return $this->error('', 'Bad Request', 400);
    }

    public function endTerm(ResultRequest $request)
    {
        $this->validateRequest($request);

        $teacher = Auth::user();

        if ($request->period === "Second Half") {

            $getsecondresult = $this->getSecondResult($request, $teacher);

            $hosId = Staff::find($request->hos_id);
            if (!$hosId || empty($request->hos_comment)) {
                return $this->error(null, "Hod needs to add comments", 400);
            }

            if (empty($getsecondresult)) {
                return $this->handleNewResult($request, $teacher, $hosId);
            } else {
                return $this->handleExistingResult($request, $teacher, $hosId, $getsecondresult);
            }
        }

        return $this->error('', 'Bad Request', 400);
    }

    public function release(ReleaseResultRequest $request)
    {
        $auth = Auth::user();

        try {

            foreach ($request->students as $student) {
                Result::where([
                    'sch_id' => $auth->sch_id,
                    'campus' => $auth->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'student_id' => $student['student_id']
                ])->update(['status' => 'released']);
            }

            return $this->success(null, "Result released");
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 500);
        }
    }

    public function hold(ReleaseResultRequest $request)
    {
        $auth = Auth::user();

        try {

            foreach ($request->students as $student) {
                Result::where([
                    'sch_id' => $auth->sch_id,
                    'campus' => $auth->campus,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'student_id' => $student['student_id']
                ])->update(['status' => 'withheld']);
            }

            return $this->success(null, "Result withheld");
        } catch (\Exception $e) {
            return $this->error(null, $e->getMessage(), 500);
        }
    }
}


