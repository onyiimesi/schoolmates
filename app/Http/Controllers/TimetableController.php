<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimetableRequest;
use App\Http\Resources\TimetableResource;
use App\Models\Timetable;
use Illuminate\Http\Request;

class TimetableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $time = TimetableResource::collection(Timetable::get());

        return [
            'status' => 'true',
            'message' => 'Timetable',
            'data' => $time
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TimetableRequest $request)
    {
        $request->validated($request->all());

        if($request->file){
            $file = $request->file;
            $folderName = 'http://127.0.0.1:8000/public/timetable';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1); 
            $image = str_replace($replace, '', $file); 

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/timetable/'.$file_name, base64_decode($image));
            
            $paths = $folderName.'/'.$file_name;
        }else{
            $paths = "";
        }

        $time = Timetable::create([
            'sch_id' => '1234',
            'period' => 'First Half',
            'term' => 'First Term',
            'session' => '2022/2023',
            'title' => $request->title,
            'description' => $request->description,
            'file' => $paths
        ]);

        return [
            "status" => 'true',
            "message" => 'Timetable Uploaded Successfully',
            "data" => $time
        ];
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
