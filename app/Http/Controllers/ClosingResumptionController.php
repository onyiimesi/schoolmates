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
    public function index(Request $request)
    {
        $user = Auth::user();
        $term = $request->query('term');
        $session = $request->query('session');

        if (!$term || !$session) {
            return $this->error(null, 'Term and session are required', 422);
        }

        $academic = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $term)
            ->where('session', $session)
            ->first();

        if (!$academic) {
            return $this->error(null, 'Academic period not found', 404);
        }

        $closingResumption = ClosingResumption::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $term)
            ->where('session', $session)
            ->first();

        if (!$closingResumption) {
            return $this->error(null, 'Closing resumption not found', 404);
        }

        $closRes = new ClosingResumptionResource($closingResumption);

        return $this->success($closRes, 'Closing resumption fetched successfully');
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

        $clos = ClosingResumption::updateOrCreate(
            [
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'term' => $request->term,
                'session' => $request->session,
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
