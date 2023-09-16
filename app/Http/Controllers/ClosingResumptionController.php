<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClosingResumptionResource;
use App\Models\AcademicPeriod;
use App\Models\ClosingResumption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClosingResumptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clos = ClosingResumptionResource::collection(ClosingResumption::get());

        return [
            'status' => 'true',
            'message' => 'GET',
            'data' => $clos
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

        $user = Auth::user();
        $academic = AcademicPeriod::first();

        $clos = ClosingResumption::create([
            'sch_id' => $user->sch_id,
            'term' => $academic->term,
            'session' => $academic->session,
            'session_ends' => $request->session_ends,
            'session_resumes' => $request->session_resumes
        ]);

        return [
            "status" => 'true',
            "message" => 'Inserted Successfully',
            "data" => $clos
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
