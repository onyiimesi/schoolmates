<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sub = SubjectResource::collection(Subject::get());

        return [
            'status' => 'true',
            'message' => 'Subjects List',
            'data' => $sub
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        $request->validated($request->all());

        $sub = Subject::create([
            'sch_id' => '1234',
            'class_name' => $request->class_name,
            'subject' => $request->subject,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $sub
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subjects = new SubjectResource($subject);

        return [
            'status' => 'true',
            'message' => 'Subject Details',
            'data' => $subjects
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $subject->update($request->all());

        $sub = new SubjectResource($subject);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $sub
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $subject->delete();

        return response(null, 204);
    }
}
