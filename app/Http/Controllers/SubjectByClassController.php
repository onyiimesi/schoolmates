<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentExcelImportResource;
use App\Http\Resources\SubjectClassResource;
use App\Http\Resources\SubjectClassResultResource;
use App\Http\Resources\SubjectResource;
use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\StudentExcelImport;
use App\Models\Subject;
use App\Models\SubjectTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class SubjectByClassController extends Controller
{
    use HttpResponses;

    public function subjectbyclass(Request $request)
    {
        $user = Auth::user();

        $academicPeriod = AcademicPeriod::select('id', 'sch_id', 'campus', 'period', 'term', 'session', 'is_current_period')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if(! $academicPeriod) {
            return $this->error(null, 'Current period has not been set.', 404);
        }

        $subjects = ClassModel::with(['subjects' => function ($query) use($academicPeriod) {
                $query->where('term', $academicPeriod->term)
                    ->where('session', $academicPeriod->session);
            }])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class)
            ->get();

        $data = SubjectClassResource::collection($subjects);

        return $this->success($data, "Subjects");
    }

    public function subjectbyId(Request $request)
    {
        $user = Auth::user();

        $academicPeriod = AcademicPeriod::select('id', 'sch_id', 'campus', 'period', 'term', 'session', 'is_current_period')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if(! $academicPeriod) {
            return $this->error(null, 'Current period has not been set.', 404);
        }

        $subjects = ClassModel::with(['subjects' => function ($query) use($academicPeriod) {
                $query->where('term', $academicPeriod->term)
                    ->where('session', $academicPeriod->session);
            }])
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $request->id)
            ->get();

        $data = SubjectClassResource::collection($subjects);

        return $this->success($data, "Subjects");
    }

    public function subjectbyCampus(){
        $user = Auth::user();

        // if ($user->teacher_type == "subject teacher") {
        //     $sub = SubjectTeacher::where('sch_id', $user->sch_id)
        //         ->where('campus', $user->campus)
        //         ->where('class_name', $user->class_assigned)
        //         ->where('staff_id', $user->id)
        //         ->get();

        //     $subs = SubjectResource::collection($sub);

        //     return $this->success($subs, "Subjects");
        // } else {
        //     $subject = ClassModel::where('sch_id', $user->sch_id)
        //         ->where('campus', $user->campus)
        //         ->where('class_name', $user->class_assigned)
        //         ->get();

        //     $subs = SubjectClassResultResource::collection($subject);

        //     return $this->success($subs, "Subjects");
        // }

        if ($user->teacher_type === "subject teacher") {
            return $this->getSubjectsForSubjectTeacher($user);
        }

        return $this->getSubjectsForOtherTeachers($user);
    }

    private function getSubjectsForSubjectTeacher($user)
    {
        $class = ClassModel::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'class_name' => $user->class_assigned
        ])
        ->firstOrFail();

        $subjects = SubjectTeacher::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'id' => $class->id,
            'staff_id' => $user->id
        ])
        ->get();

        $subjectResources = SubjectResource::collection($subjects);

        return $this->success($subjectResources, "Subjects retrieved successfully.");
    }

    private function getSubjectsForOtherTeachers($user)
    {
        $class = ClassModel::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'class_name' => $user->class_assigned
            ])
            ->firstOrFail();

        $subjects = ClassModel::with('subjects')
            ->where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'id' => $class->id
            ])
            ->get();

        $subjectResources = SubjectClassResultResource::collection($subjects);

        return $this->success($subjectResources, "Subjects retrieved successfully.");
    }

    public function studentExcelImport(){

        $subject = StudentExcelImportResource::collection(StudentExcelImport::get());

        return $this->success($subject, "");

    }

    public function subjectbyteacher(){

        $user = Auth::user();

        if ($user->teacher_type == "subject teacher") {
            $sub = SubjectTeacher::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('class_name', $user->class_assigned)
                ->where('staff_id', $user->id)
                ->get();
            $subs = SubjectResource::collection($sub);

        } else {
            $subject = ClassModel::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('class_name', $user->class_assigned)
                ->get();
            $subs = SubjectClassResultResource::collection($subject);
        }

        return $this->success($subs, "Subjects");
    }

    public function subjectbystudent(){

        $user = Auth::user();

        if($user->designation_id === 5){
            return $this->error('', 'Unauthenticated', 401);
        }

        $subject = SubjectResource::collection(
            Subject::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $user->present_class)
            ->get()
        );

        return $this->success($subject, "Student Subjects");
    }
}
