<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterSubjectRequest;
use App\Models\RegisterSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisterSubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegisterSubjectRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $regsub = RegisterSubject::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'admission_number' => $request->admission_number,
            'student_fullname' => $request->student_fullname,
            'class' => $request->class,
            'sub_class' => $request->sub_class,
            'subject' => $request->subject,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $regsub
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
