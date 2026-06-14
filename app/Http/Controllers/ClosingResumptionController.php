<?php

namespace App\Http\Controllers;

use App\Http\Resources\ClosingResumptionResource;
use App\Models\AcademicPeriod;
use App\Models\ClosingResumption;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class ClosingResumptionController extends Controller
{
    use HttpResponses;

    public function index(Request $request): JsonResponse
    {
        /** @var Staff $user */
        $user = Auth::user();

        $term = $request->query('term');
        $session = $request->query('session');

        if (!$term || !$session) {
            return $this->error(null, 'Term and session are required', 422);
        }

        $academic = AcademicPeriod::query()
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('term', $term)
            ->where('session', $session)
            ->first();

        if (!$academic) {
            return $this->error(null, 'Academic period not found', 404);
        }

        $closingResumption = ClosingResumption::query()
            ->where('sch_id', $user->sch_id)
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

    public function store(Request $request): JsonResponse
    {
        /** @var Staff $user */
        $user = Auth::user();

        $data = ClosingResumption::updateOrCreate(
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

        $msg = $data->wasRecentlyCreated ? 'Created Successfully' : 'Updated Successfully';

        return $this->success($data, $msg);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show(int $id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit(int $id): void
    {
        //
    }

    public function update(Request $request, int $id): void
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy(int $id): void
    {
        //
    }
}
