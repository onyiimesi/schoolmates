<?php

namespace App\Http\Controllers;

use App\Http\Requests\CampusRequest;
use App\Http\Resources\CampusResource;
use App\Models\Campus;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class CampusController extends Controller
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
        $campus = CampusResource::collection(
            Campus::where('sch_id', $user->sch_id)->get()
        );

        return $this->success($campus, 'Campus List');
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
            $folderName = config('services.campus_url');
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/campus/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }

        Campus::create([
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

        return $this->success(null, 'Campus Created Successfully', 201);
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

        return $this->success($campuss, 'Campus Details');
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
        if($request->image){
            $file = $request->image;
            $folderName = config('services.campus_url');
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/campus/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        } else {
            $paths = $campus->image;
        }

        $campus->update([
            'name' => $request->name,
            'email' => $request->email,
            'image' => $paths,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'state' => $request->state,
            'campus_type' => $request->campus_type,
            'is_preschool' => $request->is_preschool,
        ]);

        return $this->success(null, 'Updated Successfully');
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

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $path = null;

        if($request->hasFile('image')) {
            $name = $request->input('name');
            $path = $request->file('image')->store("campus/{$name}", 'public');

            if(App::environment(['production', 'staging'])) {
                $res = response()->json(['image_path' => asset('public/storage/' . $path)], 200);
            } else {
                $res = response()->json(['image_path' => asset('storage/' . $path)], 200);
            }

            return $res;
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
