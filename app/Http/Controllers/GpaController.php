<?php

namespace App\Http\Controllers;

use App\Models\GPA;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class GpaController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = userAuth();

        $gpas = GPA::select('id', 'sch_id', 'campus', 'min_mark', 'max_mark', 'remark', 'grade_point', 'key_range')
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get();

        return $this->success($gpas, "GPA System");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'min_mark' => 'required|integer',
            'max_mark' => 'required|integer',
            'remark' => 'required|string|max:40',
            'grade_point' => 'required',
            'key_range' => 'required|string',
        ]);

        $user = userAuth();

        GPA::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'min_mark' => $request->min_mark,
            'max_mark' => $request->max_mark,
            'remark' => $request->remark,
            'grade_point' => $request->grade_point,
            'key_range' => $request->key_range,
        ]);

        return $this->success(null, "Created Successfully", 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GPA $gpa)
    {
        $gpaDetails = $gpa->select('id', 'sch_id', 'campus', 'min_mark', 'max_mark', 'remark', 'grade_point', 'key_range')->first();

        return $this->success($gpaDetails, "GPA details");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GPA $gpa)
    {
        $gpa->update([
            'min_mark' => $request->min_mark,
            'max_mark' => $request->max_mark,
            'remark' => $request->remark,
            'grade_point' => $request->grade_point,
            'key_range' => $request->key_range,
        ]);

        return $this->success(null, "Updated Successfully");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GPA $gpa)
    {
        $gpa->delete();

        return $this->success(null, "Deleted Successfully");
    }
}
