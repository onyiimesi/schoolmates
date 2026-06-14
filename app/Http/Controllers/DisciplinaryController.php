<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisciplinaryRequest;
use App\Models\DisciplinaryAction;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class DisciplinaryController extends Controller
{
    use HttpResponses;

    public function index(): void
    {
        //
    }

    public function store(DisciplinaryRequest $request): JsonResponse
    {
        $request->validated($request->all());

        /** @var Staff $user */
        $user = Auth::user();

        $dis = DisciplinaryAction::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'offence_type' => $request->offence_type,
            'offence_action' => $request->offence_action,
            'fine' => $request->fine,
        ]);

        return $this->success($dis, "Created Successfully", 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     */
    public function edit($id): void
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
    public function destroy($id): void
    {
        //
    }
}
