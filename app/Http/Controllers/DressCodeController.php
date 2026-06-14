<?php

namespace App\Http\Controllers;

use App\Http\Requests\DressCodeRequest;
use App\Http\Resources\DressCodeResource;
use App\Models\DressCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class DressCodeController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function index(): array
    {
        /** @var Staff $user */
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
     * @return array<string, mixed>
     */
    public function store(DressCodeRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff $user */
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id): void
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
