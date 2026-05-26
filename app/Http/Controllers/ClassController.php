<?php

namespace App\Http\Controllers;

use App\Actions\GetCampusAction;
use App\Http\Requests\ClassRequest;
use App\Http\Resources\ClassResource;
use App\Http\Resources\PreSchoolResource;
use App\Models\AcademicPeriod;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\PreSchool;
use App\Models\Result;
use App\Models\Staff;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    use HttpResponses;

    public function index()
    {
        $user = Auth::user();

        $staff = Staff::where('sch_id', $user->sch_id)
            ->where('username', $user->username)
            ->first();

        if (! $staff) {
            return $this->error(null, 'Staff not found', 404);
        }

        $campus = $staff->getCampus();

        if (! $campus) {
            return $this->error(null, 'Campus not found', 404);
        }

        if (! $campus->is_preschool) {
            $academicPeriod = AcademicPeriod::where('sch_id', $user->sch_id)
                ->where('campus', $campus->name)
                ->where('is_current_period', true)
                ->first();

            $query = ClassModel::where('sch_id', $user->sch_id)
                ->with([
                    'classTeacher',
                    'subjectTeachers' => function ($q) use ($academicPeriod, $user) {
                        if ($academicPeriod) {
                            $q->where('sch_id', $user->sch_id)
                            ->where('campus', $user->campus)
                            ->where('term', $academicPeriod->term)
                            ->where('session', $academicPeriod->session);
                        }
                    },
                    'subjectTeachers.staff',
                    'subjectTeachers.subjects' => function ($q) use ($academicPeriod, $user) {
                        if ($academicPeriod) {
                            $q->where('sch_id', $user->sch_id)
                            ->where('campus', $user->campus)
                            ->where('term', $academicPeriod->term)
                            ->where('session', $academicPeriod->session);
                        }
                    },
                ])
                ->when($user->designation_id != 6, function ($q) use ($campus) {
                    $q->where('campus', $campus->name);
                })
                ->get();

            $classes = ClassResource::collection($query);
        } else {
            $classData = PreSchool::where('sch_id', $user->sch_id)
                ->where('campus', $campus->name)
                ->get();

            $classes = $classData->map(function ($class) {
                return [
                    'id' => (string)$class->id,
                    'attributes' => [
                        'campus' => (string)$class->campus,
                        'class_name' => (string)$class->name,
                        'teachers' => [],
                        'subjects' => [],
                    ]
                ];
            });
        }

        return $this->success($classes, 'Class List');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(ClassRequest $request)
    {
        $user = Auth::user();
        $campus = Campus::where('name', $request->campus)->first();

        if (! $campus) {
            return $this->error(null, 'Campus not found', 404);
        }

        if (
            ClassModel::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $request->class_name)
            ->exists()
        ) {
            return $this->error(null, 'Class already exists', 409);
        }

        ClassModel::create([
            'sch_id' => $user->sch_id,
            'campus' => $campus->name,
            'class_name' => $request->class_name,
            'campus_type' => $campus->campus_type,
            'sub_class' => $request->sub_class
        ]);

        return $this->success(null, 'Class Created Successfully');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, ClassModel $class)
    {
        $validated = $request->validate([
            'class_name' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        if (
            ClassModel::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('class_name', $validated['class_name'])
            ->exists()
        ) {
            return $this->error(null, 'Class already exists', 409);
        }

        if ($class->class_name !== $validated['class_name']) {
            Result::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('class_name', $class->class_name)
                ->update([
                    'class_name' => $validated['class_name']
                ]);
        }

        $class->update($validated);
        $classs = new ClassResource($class);

        return $this->success($classs, 'Class Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(ClassModel $class)
    {
        SubjectClass::where('class_id', $class->id)->delete();
        $class->delete();

        return response(null, 204);
    }
}
