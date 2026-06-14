<?php

namespace App\Http\Controllers;

use App\Http\Requests\CodeConductRequest;
use App\Models\CodeCoduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;

class CodeConductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): void
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @return array<string, mixed>
     */
    public function store(CodeConductRequest $request): array
    {
        $request->validated($request->all());

        /** @var Staff $user */
        $user = Auth::user();

        $code = CodeCoduct::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,  
            'rule' => $request->rule,
            'description' => $request->description,
            'apply_to' => $request->apply_to,
        ]);

        return [
            "status" => 'true',
            "message" => 'Created Successfully',
            "data" => $code
        ];
    }

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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
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
