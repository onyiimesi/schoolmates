<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClosingResumptionResource;
use App\Models\AcademicPeriod;
use App\Models\ClosingResumption;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClosingResumptionController extends Controller
{
    use HttpResponses;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $academic = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if(!$academic) {
            return $this->error(null, 'Academic period not found', 404);
        }

        $closingResumption = ClosingResumption::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $academic->term)
            ->where('session', $academic->session)
            ->firstOrFail();

        $clos = new ClosingResumptionResource($closingResumption);

        return $this->success($clos, 'Closing resumption fetched successfully');
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
        $academic = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if(!$academic) {
            return $this->error(null, 'Academic period not found', 404);
        }

        $clos = ClosingResumption::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $academic->term,
                'session' => $academic->session,
            ],
            [
                'session_ends' => $request->session_ends,
                'session_resumes' => $request->session_resumes,
            ]
        );

        $msg = $clos->wasRecentlyCreated ? 'Created Successfully' : 'Updated Successfully';

        return $this->success($clos, $msg);
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
