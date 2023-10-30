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
        $maxi = MaximumScoresResource::collection(MaximunScores::where('campus', $user->campus)->first());

        return [
            'status' => 'true',
            'message' => 'Subjects',
            'data' => $maxi
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $maxi = MaximunScores::create([
            'midterm' => $request->midterm,
            'exam' => $request->exam,
            'total' => $request->total,
        ]);

        return [
            "status" => 'true',
            "message" => 'Inserted Successfully',
            "data" => $maxi
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
