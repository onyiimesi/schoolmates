<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Http\Resources\ClassResource;
use App\Models\Campus;
use App\Models\ClassModel;
use App\Models\Result;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $query = ClassModel::where('sch_id', $user->sch_id);

        if ($user->designation_id != 6) {
            $query->where('campus', $user->campus);
        }

        $classes = ClassResource::collection($query->get());

        return $this->success($classes, 'Class List');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassModel $class)
    {
        SubjectClass::where('class_id', $class->id)->delete();
        $class->delete();

        return response(null, 204);
    }
}
