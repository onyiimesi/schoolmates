<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AssignSubjectsController extends Controller
{
    use HttpResponses;

    public function assign(Request $request)
    {
        $user = Auth::user();

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('is_current_period', true)
            ->first();

        if (! $period) {
            return $this->error(null, 'Academic period not set', 404);
        }

        $class = ClassModel::find($request->class_id);

        if (! $class) {
            return $this->error(null, 'Class does not exist', 400);
        }

        DB::transaction(function () use ($user, $request, $period, $class) {
            foreach ($request->subjects as $subjectData) {
                SubjectClass::updateOrCreate(
                    [
                        'sch_id' => $user->sch_id,
                        'campus' => $user->campus,
                        'term' => $period->term,
                        'session' => $period->session,
                        'class_id' => $request->class_id,
                        'subject' => $subjectData['name'],
                    ],
                    [
                        'class_name' => $class->class_name,
                    ]
                );
            }
        });

        return $this->success(null, 'Subjects assigned successfully');
    }
}
