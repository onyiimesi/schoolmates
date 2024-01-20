<?php

namespace App\Http\Controllers;

use App\Http\Requests\DressCodeRequest;
use App\Http\Resources\DressCodeResource;
use App\Models\DressCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DressCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $dress = DressCodeResource::collection(
            DressCode::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return [
            'status' => 'true',
            'message' => 'Dress Code List',
            'data' => $dress
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DressCodeRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $dress = DressCode::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'day' => $request->day,
            'wear' => $request->wear,
            'description' => $request->description
        ]);

        return [
            "status" => 'true',
            "message" => 'Added Successfully',
            "data" => $dress
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
