<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSchoolResultRequest;
use App\Models\PreSchoolResultExtraCurricular;
use App\Models\PreSchoolResult;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreSchoolResultController extends Controller
{
    use HttpResponses;

    public function result(PreSchoolResultRequest $request)
    {
        $request->validated($request->all());
        $teacher = Auth::user();

        $isFirstHalf = $request->period === 'First Half';
        $period = $isFirstHalf ? 'First Half' : 'Second Half';

        $existingResult = $this->getExistingResult($teacher, $request, $period);

        $hosId = $this->getHOS($teacher);
        $teacherDetails = $this->getTeacherDetails($request->teacher_id);

        if (empty($existingResult)) {
            $resultData = $this->prepareResultData($teacher, $request, $hosId, $teacherDetails);
            $computedResult = PreSchoolResult::create($resultData);

            $this->handleExtraCurricularActivities($computedResult, $request->extra_curricular_activities);

            return $this->success(null, 'Computed Successfully', 201);
        } else {
            $updateData = $this->prepareResultData($teacher, $request, $hosId, $teacherDetails);
            $existingResult->update($updateData);

            $existingResult->preschoolresultextracurricular()->delete();
            $this->handleExtraCurricularActivities($existingResult, $request->extra_curricular_activities);

            return $this->success(null, 'Result Updated Successfully');
        }
    }

    private function getExistingResult($teacher, $request, $period)
    {
        return PreSchoolResult::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where('student_id', $request->student_id)
            ->where('period', $period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->first();
    }

    private function getHOS($teacher)
    {
        return Staff::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where('designation_id', 3)
            ->where('status', 'Active')
            ->first();
    }

    private function getTeacherDetails($teacherId)
    {
        $teacher = Staff::find($teacherId);
        return [
            'fullname' => $teacher->surname . ' ' . $teacher->firstname,
            'signature' => $teacher->teacher_signature,
        ];
    }

    private function prepareResultData($teacher, $request, $hosId, $teacherDetails)
    {
        return [
            'sch_id' => $teacher->sch_id,
            'campus' => $teacher->campus,
            'student_id' => $request->student_id,
            'student_fullname' => $request->student_fullname,
            'admission_number' => $request->admission_number,
            'class_name' => $request->class_name,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'school_opened' => $request->school_opened,
            'times_present' => $request->times_present,
            'times_absent' => $request->times_absent,
            'evaluation_report' => $request->evaluation_report,
            'cognitive_development' => $request->cognitive_development,
            'teacher_comment' => $request->teacher_comment,
            'teacher_id' => $request->teacher_id,
            'teacher_fullname' => $teacherDetails['fullname'],
            'teacher_signature' => $teacherDetails['signature'],
            'hos_comment' => $request->hos_comment,
            'hos_id' => $hosId->id ?? null,
            'hos_fullname' => $hosId ? $hosId->surname . ' ' . $hosId->firstname : null,
            'hos_signature' => $hosId->signature ?? null,
            'status' => 'active',
        ];
    }

    private function handleExtraCurricularActivities($result, $activities)
    {
        foreach ($activities as $extra) {
            $ext = new PreSchoolResultExtraCurricular($extra);
            $result->preschoolresultextracurricular()->save($ext);
        }
    }

}
