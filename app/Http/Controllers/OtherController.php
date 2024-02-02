<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignationResource;
use App\Models\Designation;
use App\Models\ExtraCurricular;
use App\Models\PreSchoolExtraCurricular;
use App\Models\Schools;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OtherController extends Controller
{

    use HttpResponses;

    public function extra(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        ExtraCurricular::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Created Successfully',
        ]);

    }

    public function getextra() : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = ExtraCurricular::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get();

        return response()->json([
            'status' => 'true',
            'message' => 'List',
            'data' => $extra->map(function($name) {
                return [
                    "id" => $name->id,
                    "name" => $name->name
                ];
            })->toArray()
        ]);
    }

    public function delextra(Request $request) : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = ExtraCurricular::findorFail($request->id);

        $extra->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'Deleted Successfully'
        ]);
    }

    public function role()
    {
        $user = Auth::user();

        if($user->designation_id === 6){

            $school = Schools::where('sch_id', $user->sch_id)->first();

            if($school->pricing_id == 1 || $school->pricing_id == 3){
                $roleNot = ['2' ,'6', '7'];
            }else{
                $roleNot = ['6', '7'];
            }

            return DesignationResource::collection(
                Designation::whereNotIn('id', $roleNot)->get()
            );

        } else if($user->designation_id === 1){

            $school = Schools::where('sch_id', $user->sch_id)->first();

            if($school->pricing_id == 1 || $school->pricing_id == 2){
                $roleNot = ['1', '2' ,'6', '7'];
            }else{
                $roleNot = ['1', '6', '7'];
            }

            return DesignationResource::collection(Designation::whereNotIn('id', $roleNot)->get());

        }
    }

    public function preextra(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        PreSchoolExtraCurricular::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Created Successfully',
        ]);

    }

    public function pregetextra() : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = PreSchoolExtraCurricular::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get();

        return response()->json([
            'status' => 'true',
            'message' => 'List',
            'data' => $extra->map(function($name) {
                return [
                    "id" => $name->id,
                    "name" => $name->name
                ];
            })->toArray()
        ]);
    }

    public function predelextra(Request $request) : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = PreSchoolExtraCurricular::findorFail($request->id);

        $extra->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'Deleted Successfully'
        ]);
    }
}
