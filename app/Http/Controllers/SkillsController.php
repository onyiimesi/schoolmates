<?php

namespace App\Http\Controllers;

use App\Http\Requests\SkillsRequest;
use App\Http\Resources\SkillsResource;
use App\Models\Skills;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkillsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();

        $skills = SkillsResource::collection(
            Skills::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)->get()
        );

        return [
            'status' => 'true',
            'message' => 'Skills',
            'data' => $skills
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SkillsRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        $skill = Skills::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('skill_type', $request->skill_type)->first();

        if(empty($skill)){
            $skills = Skills::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'skill_type' => $request->skill_type,
                'attribute' => $request->attribute
            ]);

            return [
                'status' => 'true',
                'message' => 'Added Successfully',
                'data' => $skills
            ];

        }else if(!empty($skill)){

            $skill->update([
                'attribute' => $request->attribute
            ]);

            return [
                'status' => 'true',
                'message' => 'Added Successfully',
                'data' => $skill
            ];
        }

        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Skills $skill)
    {
        $skills = new SkillsResource($skill);

        return [
            'status' => 'true',
            'message' => '',
            'data' => $skills
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skills $skill)
    {
        $skill->update($request->all());

        $skills = new SkillsResource($skill);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $skills
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Skills $skills)
    {
        $skills->delete();

        return response(null, 204);
    }
}
