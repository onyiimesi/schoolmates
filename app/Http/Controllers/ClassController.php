<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClassRequest;
use App\Http\Resources\ClassResource;
use App\Models\Campus;
use App\Models\ClassModel;
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

        if ($user->designation_id == 6) {
            $data = ClassResource::collection(
                ClassModel::where('sch_id', $user->sch_id)
                ->get()
            );

        }else {
            $data = ClassResource::collection(
                ClassModel::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get()
            );
        }

        return $this->success($data, 'Class List');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClassRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();
        $campus = Campus::where('name', $request->campus)->first();

        ClassModel::create([
            'sch_id' => $user->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'class_name' => $request->class_name,
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

        $class->update($request->all());

        $classs = new ClassResource($class);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $classs
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassModel $class)
    {
        $class->delete();

        return response(null, 204);
    }
}
