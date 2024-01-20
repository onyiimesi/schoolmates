<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradingSystemRequest;
use App\Http\Resources\GradingSystemResource;
use App\Models\GradingSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradingSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $grading = GradingSystemResource::collection(
            GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Grade List',
            'data' => $grading
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradingSystemRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        $grading = GradingSystem::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'score_from' => $request->score_from,
            'score_to' => $request->score_to,
            'grade' => $request->grade,
            'remark' => $request->remark,
            'created_by' => $user->surname .' '. $user->firstname .' '. $user->middlename,
        ]);

        return [
            "status" => 'true',
            "message" => 'Created Successfully',
            "data" => $grading
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GradingSystem $grading)
    {
        $grades = new GradingSystemResource($grading);

        return [
            'status' => 'true',
            'message' => 'Grade Details',
            'data' => $grades
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, GradingSystem $grading)
    {

        $grading->update($request->all());

        $grades = new GradingSystemResource($grading);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $grades
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(GradingSystem $grading)
    {
        $grading->delete();

        return response(null, 204);
    }
}
