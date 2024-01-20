<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampusRequest;
use App\Http\Resources\CampusResource;
use App\Models\Campus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $campus = CampusResource::collection(
            Campus::where('sch_id', $user->sch_id)->get()
        );

        return [
            'status' => 'true',
            'message' => 'Campus List',
            'data' => $campus
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CampusRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        $paths = "";
        if($request->image){
            $file = $request->image;
            $folderName = 'http://127.0.0.1:8000/public/campus';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/campus/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }

        $campus = Campus::create([
            'sch_id' => $user->sch_id,
            'name' => $request->name,
            'email' => $request->email,
            'image' => $paths,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'campus_type' => $request->campus_type,
            'is_preschool' => $request->is_preschool,
            'status' => 'active',
            'created_by' => $user->surname .' '. $user->firstname .' '. $user->middlename,
        ]);

        return [
            "status" => 'true',
            "message" => 'Campus Created Successfully',
            "data" => $campus
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Campus $campus)
    {
        $campuss = new CampusResource($campus);

        return [
            'status' => 'true',
            'message' => 'Campus Details',
            'data' => $campuss
        ];
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Campus $campus)
    {

        $campus->update($request->all());

        $campu = new CampusResource($campus);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $campu
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Campus $campus)
    {
        $campus->delete();

        return response(null, 204);
    }
}
