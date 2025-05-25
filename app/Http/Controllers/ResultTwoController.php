<?php

namespace App\Http\Controllers;

use App\Enum\PeriodicName;
use App\Enum\ResultStatus;
use App\Http\Requests\MidtermRequest;
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

    public function mid(MidtermRequest $request)
    {
        $teacher = Auth::user();

        if ($request->period !== PeriodicName::FIRSTHALF) {
            return $this->error(null, "Unsupported period value: {$request->period}", 400);
        }

        return DB::transaction(function () use ($teacher, $request) {
            $match = [
                'sch_id' => $teacher->sch_id,
                'campus' => $teacher->campus,
                'student_id' => $request->student_id,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
            ];

            $data = [
                'campus_type' => $teacher->campus_type,
                'teacher_id' => $teacher->id,
                'student_fullname' => $request->student_fullname,
                'admission_number' => $request->admission_number,
                'class_name' => $request->class_name,
                'computed_midterm' => true,
                'result_type' => $request->result_type,
                'teacher_comment' => $request->teacher_comment,
                'status' => ResultStatus::NOTRELEASED->value,
            ];

            $result = Result::updateOrCreate($match, $data);

            if (!$result->wasRecentlyCreated) {
                $result->studentscore()->delete();
            }

            $this->saveStudentScores($result, $request->results);

            $message = $result->wasRecentlyCreated ? 'Computed Successfully' : 'Updated Successfully';
            return $this->success(null, $message, $result->wasRecentlyCreated ? 201 : 200);
        });
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
                ->update(['status' => ResultStatus::RELEASED->value]);

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
                ->update(['status' => ResultStatus::WITHELD->value]);

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
        $request->validate([
            'campus' => 'required|string',
            'period' => 'required|string',
            'term' => 'required|string',
            'sheet_ids' => 'required|array',
            'sheet_ids.*' => 'required|integer|exists:sheets,id',
        ]);

        return $this->resultService->saveSheetSections($request);
    }

    public function getSchoolSheetSettings()
    {
        return $this->resultService->getSchoolSheetSettings();
    }

}


