<?php

namespace App\Http\Controllers;

use App\Http\Resources\MaximumScoresResource;
use App\Models\MaximunScores;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaximumScoresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $maxi = MaximunScores::where('campus', $user->campus)->first();

        if ($maxi) {
            $maxiResource = new MaximumScoresResource($maxi);

            return [
                'status' => 'true',
                'message' => 'Maximum Scores',
                'data' => $maxiResource
            ];
        } else {
            return [
                'status' => 'false',
                'message' => 'Maximum Scores not found',
                'data' => []
            ];
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $scores = MaximunScores::where('campus', $user->campus)->first();

        if(empty($scores)){

            MaximunScores::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'midterm' => $request->midterm,
                'first_assessment' => $request->first_assessment,
                'second_assessment' => $request->second_assessment,
                'has_two_assessment' => $request->has_two_assessment,
                'exam' => $request->exam,
                'total' => $request->total
            ]);

        }else {
            $scores->update([
                'midterm' => $request->midterm,
                'first_assessment' => $request->first_assessment,
                'second_assessment' => $request->second_assessment,
                'has_two_assessment' => $request->has_two_assessment,
                'exam' => $request->exam,
                'total' => $request->total
            ]);
        }

        return [
            "status" => 'true',
            "message" => 'Saved Successfully',
        ];
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
