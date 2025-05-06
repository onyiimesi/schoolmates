<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\ResultStatus;
use App\Http\Requests\ReleaseResultRequest;
use App\Http\Requests\ResultRequest;
use App\Models\Result;
use App\Models\Staff;
use App\Services\ResultService;
use App\Traits\HttpResponses;
use App\Traits\ResultBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\ResponseCache\Facades\ResponseCache;

class ResultTwoController extends Controller
{
    use HttpResponses, ResultBase;

    public function __construct(
        protected ResultService $resultService
    )
    {}

    public function mid(Request $request)
    {
        $request->validate([
            'student_id' => ['required', 'exists:students,id'],
            'student_fullname' => ['required', 'string'],
            'admission_number' => ['required', 'string', 'max:255'],
            'class_name' => ['required', 'string', 'max:255'],
            'period' => ['required', 'string', 'max:255'],
            'term' => ['required', 'string', 'max:255'],
            'session' => ['required', 'string', 'max:255'],
            'result_type' => 'required|in:first_assesment,second_assesment,midterm'
        ]);

        $teacher = Auth::user();

        if ($request->period === PeriodicName::FIRSTHALF) {
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

        if ($request->period === PeriodicName::SECONDHALF) {

            $getsecondresult = $this->getSecondResult($request, $teacher);

            $hosId = Staff::find($request->hos_id);
            if (!$hosId && empty($request->hos_comment)) {
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
        $studentIds = collect($request->students)->pluck('student_id')->toArray();

        DB::beginTransaction();
        try {
            Result::where('sch_id', $auth->sch_id)
                ->where('campus', $auth->campus)
                ->where('period', $request->period)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->whereIn('student_id', $studentIds)
                ->update(['status' => ResultStatus::RELEASED]);

            ResponseCache::clear();
            DB::commit();
            return $this->success(null, "Result released");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error releasing results', ['error' => $e->getMessage()]);
            return $this->error(null, "An error occurred while releasing results", 500);
        }
    }

    public function hold(ReleaseResultRequest $request)
    {
        $auth = Auth::user();
        $studentIds = collect($request->students)->pluck('student_id')->toArray();

        DB::beginTransaction();
        try {
            Result::where('sch_id', $auth->sch_id)
                ->where('campus', $auth->campus)
                ->where('period', $request->period)
                ->where('term', $request->term)
                ->where('session', $request->session)
                ->whereIn('student_id', $studentIds)
                ->update(['status' => ResultStatus::WITHELD]);

            ResponseCache::clear();

            DB::commit();
            return $this->success(null, "Result withheld");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error withholding results', ['error' => $e->getMessage()]);
            return $this->error(null, "An error occurred while withholding results", 500);
        }
    }

    public function getSettings()
    {
        return $this->resultService->getSettings();
    }

    public function storeSettings(Request $request)
    {
        $request->validate([
            'campus' => ['required', 'string', 'max:255'],
            'score_option_id' => ['required', 'integer', 'exists:score_options,id']
        ]);

        return $this->resultService->storeSettings($request);
    }

    public function getSchoolScoreSettings()
    {
        return $this->resultService->getSchoolScoreSettings();
    }

    public function getSheetSections()
    {
        return $this->resultService->getSheetSections();
    }

    public function saveSheetSections(Request $request)
    {
        return $this->resultService->saveSheetSections($request);
    }

    public function getSchoolSheetSettings()
    {
        return $this->resultService->getSchoolSheetSettings();
    }

}


