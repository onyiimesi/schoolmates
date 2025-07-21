<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AssignSubjectsController extends Controller
{
    use HttpResponses;

    public function assign(Request $request)
    {
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if (! $period) {
            return $this->error(null, 'Academic period not set', 404);
        }

        $subjects = SubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_id', $request->class_id)
            ->get();

        $class = ClassModel::find($request->class_id);

        if (! $class) {
            return $this->error(null, 'Class does not exist', 400);
        }

        // if (! $subjects->isEmpty()) {
        //     $subjects->each(function ($subject) {
        //         $subject->delete();
        //     });
        // }

        foreach ($request->subjects as $subjectData) {
            SubjectClass::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $period->term,
                'session' => $period->session,
                'class_id' => $request->class_id,
                'class_name' => $class->class_name,
                'subject' => $subjectData['name']
            ]);
        }

        return $this->success(null, 'Subjects assigned successfully');
    }
}
