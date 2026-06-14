<?php

namespace App\Http\Controllers;

use App\Http\Requests\GradingSystemRequest;
use App\Http\Resources\GradingSystemResource;
use App\Models\GradingSystem;
use App\Traits\HttpResponses;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GradingSystemController extends Controller
{
    use HttpResponses;

    public function index(): JsonResponse
    {
        $user = userAuth();

        $grading = GradingSystemResource::collection(
            GradingSystem::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return $this->success($grading, "Grading System");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GradingSystemRequest $request)
    {
        $request->validated($request->all());

        $user = userAuth();

        $grading = GradingSystem::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'score_from' => $request->score_from,
            'score_to' => $request->score_to,
            'grade' => $request->grade,
            'remark' => $request->remark,
            'created_by' => "{$user->surname} {$user->firstname} {$user->middlename}",
        ]);

        return $this->success($grading, "Created successfully", 201);
    }

    public function show(GradingSystem $grading): JsonResponse
    {
        $grades = new GradingSystemResource($grading);

        return $this->success($grades, "Grading details");
    }

    public function update(Request $request, GradingSystem $grading): JsonResponse
    {
        $grading->update($request->all());
        $grades = new GradingSystemResource($grading);

        return $this->success($grades, "Updated successfully");
    }

    public function destroy(GradingSystem $grading): Response|ResponseFactory
    {
        $grading->delete();

        return response(null, 204);
    }
}
