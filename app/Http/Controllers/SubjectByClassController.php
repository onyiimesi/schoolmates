<?php

namespace App\Http\Controllers;

use App\Http\Resources\StudentExcelImportResource;
use App\Http\Resources\SubjectClassResource;
use App\Http\Resources\SubjectClassResultResource;
use App\Http\Resources\SubjectResource;
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

    public function subjectbyclass(Request $request){

        $user = Auth::user();

        $subject = SubjectClassResource::collection(
            ClassModel::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class)
            ->get()
        );

        return $this->success($subject, "Subjects");
    }

    public function subjectbyId(Request $request){

        $user = Auth::user();
        $subject = SubjectClassResource::collection(
            ClassModel::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $request->id)
            ->get()
        );

        return $this->success($subject, "Subjects");
    }

    public function subjectbyCampus(){
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
