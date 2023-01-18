<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinaryRequest;
use App\Models\DisciplinaryAction;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
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
    public function store(DisciplinaryRequest $request)
    {
        $request->validated($request->all());

        $dis = DisciplinaryAction::create([
            'offence_type' => $request->offence_type,
            'offence_action' => $request->offence_action,
            'fine' => $request->fine,
        ]);

        return [
            "status" => 'true',
            "message" => 'Created Successfully',
            "data" => $dis
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
