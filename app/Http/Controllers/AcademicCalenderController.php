<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicCalenderRequest;
use App\Http\Resources\AcademicCalenderResource;
use App\Models\AcademicCalender;
use App\Models\AcademicPeriod;
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
        $user = Auth::user();
        $aca = AcademicCalenderResource::collection(
            AcademicCalender::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)->get()
        );

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
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)->first();

        if($request->file){
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

            $file = $request->file;
            $baseFolder = 'calender';
            $userFolder = $cleanSchId;
            $folderPath = public_path($baseFolder . '/' . $userFolder);
            $folderName = env('CALENDAR_FOLDER') . '/' . $cleanSchId;
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;

            if (!file_exists(public_path($baseFolder))) {
                mkdir(public_path($baseFolder), 0777, true);
            }

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            file_put_contents($folderPath.'/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }else{
            $paths = "";
        }

        $aca = AcademicCalender::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $period->period,
            'term' => $period->term,
            'session' => $period->session,
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
