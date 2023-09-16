<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicCalenderRequest;
use App\Http\Resources\AcademicCalenderResource;
use App\Models\AcademicCalender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicCalenderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aca = AcademicCalenderResource::collection(AcademicCalender::get());

        return [
            'status' => 'true',
            'message' => 'Calender List',
            'data' => $aca
        ];
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcademicCalenderRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();

        if($request->file){
            $file = $request->file;
            $folderName = 'https://schoolmate.powershellerp.com/public/calender';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/calender/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }else{
            $paths = "";
        }

        $aca = AcademicCalender::create([
            'sch_id' => '1234',
            'period' => $user->period,
            'term' => $user->term,
            'session' => $user->session,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $paths
        ]);

        return [
            "status" => 'true',
            "message" => 'Calender Uploaded Successfully',
            "data" => $aca
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
