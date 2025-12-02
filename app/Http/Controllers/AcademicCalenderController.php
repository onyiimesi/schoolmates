<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcademicCalenderRequest;
use App\Http\Resources\AcademicCalenderResource;
use App\Models\AcademicCalender;
use App\Models\AcademicPeriod;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AcademicCalenderController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = Auth::user();
        $data = AcademicCalenderResource::collection(
            AcademicCalender::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)->get()
        );

        return $this->success($data, 'Academic calender list');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(AcademicCalenderRequest $request): JsonResponse
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
        }

        $data = AcademicCalender::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $period->period,
            'term' => $period->term,
            'session' => $period->session,
            'title' => $request->title,
            'description' => $request->description,
            'file' => $paths ?? null
        ]);

        return $this->success($data, 'Calender uploaded successfully', 201);
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
