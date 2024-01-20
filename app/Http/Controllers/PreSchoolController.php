<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSchoolRequest;
use App\Http\Resources\PreSchoolResource;
use App\Models\PreSchool;
use App\Models\PreSchoolSubjectClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $preschool = PreSchoolResource::collection(
            PreSchool::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)->get()
        );

        return [
            'status' => '',
            'message' => 'Preschools',
            'data' => $preschool
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PreSchoolRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        $preschool = PreSchool::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name
        ]);

        return [
            'status' => '',
            'message' => 'Created Successfully',
            'data' => $preschool
        ];

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PreSchool $preschool)
    {
        $preschools = new PreSchoolResource($preschool);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $preschools
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PreSchool $preschool)
    {
        $user = Auth::user();

        $preschool->update($request->all());
        PreSchoolSubjectClass::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('class_id', $preschool->id)->update([
            'class' => $preschool->name
        ]);

        $preschools = new PreSchoolResource($preschool);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $preschools
        ];

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PreSchool $preschool)
    {
        $preschool->delete();

        return response(null, 204);
    }
}
