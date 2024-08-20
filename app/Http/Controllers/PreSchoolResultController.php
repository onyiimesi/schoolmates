<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSchoolResultRequest;
use App\Models\PreSchoolResultExtraCurricular;
use App\Models\PreSchoolResult;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreSchoolResultController extends Controller
{
    public function result(PreSchoolResultRequest $request)
    {
        $request->validated($request->all());

        $teacher = Auth::user();

        if($request->period == 'First Half'){

            $getresult = PreSchoolResult::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'First Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            $hosId = Staff::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where('designation_id', 3)
            ->where('status', 'Active')
            ->first();

            if(empty($getresult)){

                $compute = PreSchoolResult::create([
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
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $hosId?->id,
                    'hos_fullname' => $hosId?->surname . ' '. $hosId?->firstname,
                    'hos_signature' => $hosId?->signature,
                    'status' => 'active',
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                    "data" => $compute
                ];

            }elseif(!empty($getresult)){

                $getresult->update([
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
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $request->hos_id,
                ]);

                return [
                    "status" => 'true',
                    "message" => 'Result Updated Successfully',
                    "data" => $getresult
                ];

            }

        }elseif($request->period == 'Second Half'){

            $getsecondresult = PreSchoolResult::where('sch_id', $teacher->sch_id)
            ->where('campus', $teacher->campus)
            ->where("student_id", $request->student_id)
            ->where("period", 'Second Half')
            ->where("term", $request->term)
            ->where("session", $request->session)->first();

            $hosId = Staff::where('campus', $teacher->campus)
            ->where('designation_id', 3)
            ->where('status', 'Active')
            ->first();
            $teachId = Staff::find($request->teacher_id);

            if(empty($getsecondresult)){

                $compute = PreSchoolResult::create([
                    'sch_id' => $teacher->sch_id,
                    'campus' => $teacher->campus,
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'evaluation_report' => $request->evaluation_report,
                    'school_opened' => $request->school_opened,
                    'times_present' => $request->times_present,
                    'times_absent' => $request->times_absent,
                    'cognitive_development' => $request->cognitive_development,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teachId->surname . ' '. $teachId->firstname,
                    'teacher_signature' => $teachId->teacher_signature,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $hosId->id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'hos_signature' => $hosId->signature,
                    'status' => 'active',
                ]);

                foreach ($request->extra_curricular_activities as $extra) {
                    $ext = new PreSchoolResultExtraCurricular($extra);
                    $compute->preschoolresultextracurricular()->save($ext);
                }

                return [
                    "status" => 'true',
                    "message" => 'Computed Successfully',
                    "data" => $compute
                ];

            }elseif(!empty($getsecondresult)){

                $getsecondresult->update([
                    'sch_id' => $teacher->sch_id,
                    'campus' => $teacher->campus,
                    'student_id' => $request->student_id,
                    'student_fullname' => $request->student_fullname,
                    'admission_number' => $request->admission_number,
                    'class_name' => $request->class_name,
                    'period' => $request->period,
                    'term' => $request->term,
                    'session' => $request->session,
                    'evaluation_report' => $request->evaluation_report,
                    'cognitive_development' => $request->cognitive_development,
                    'school_opened' => $request->school_opened,
                    'times_present' => $request->times_present,
                    'times_absent' => $request->times_absent,
                    'teacher_comment' => $request->teacher_comment,
                    'teacher_id' => $request->teacher_id,
                    'teacher_fullname' => $teachId->surname . ' '. $teachId->firstname,
                    'teacher_signature' => $teachId->teacher_signature,
                    'hos_comment' => $request->hos_comment,
                    'hos_id' => $hosId->id,
                    'hos_fullname' => $hosId->surname . ' '. $hosId->firstname,
                    'hos_signature' => $hosId->signature,
                    'status' => 'active',
                ]);

                $getsecondresult->preschoolresultextracurricular()->delete();
                foreach ($request->extra_curricular_activities as $extra) {
                    $ext = new PreSchoolResultExtraCurricular($extra);
                    $getsecondresult->preschoolresultextracurricular()->save($ext);
                }

                return [
                    "status" => 'true',
                    "message" => 'Updated Successfully',
                    "data" => $getsecondresult
                ];

            }
        }
    }
}
