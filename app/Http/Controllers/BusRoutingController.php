<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusRoutingRequest;
use App\Models\AcademicPeriod;
use App\Models\BusRouting;
use App\Models\Schools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusRoutingController extends Controller
{
    public function route(BusRoutingRequest $request){
        $user = Auth::user();

        $request->validated($request->all());

        $sch = Schools::where('sch_id', $user->sch_id)
        ->first();
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();

        $paths = "";
        if($request->driver_image){
            $file = $request->driver_image;
            $folderName = 'https://schoolmate.powershellerp.com/public/routes/drivers';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/routes/drivers/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }

        $pathss = "";
        if($request->conductor_image){
            $file = $request->conductor_image;
            $folderName = 'https://schoolmate.powershellerp.com/public/routes/conductors';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $sig = str_replace($replace, '', $file);

            $sig = str_replace(' ', '+', $sig);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/routes/conductors/'.$file_name, base64_decode($sig));

            $pathss = $folderName.'/'.$file_name;
        }

        $bus = BusRouting::create([
            'sch_id' => $sch->sch_id,
            'campus' => $user->campus,
            'term' => $period->term,
            'session' => $period->session,
            'admission_number' => $request->admission_number,
            'student_id' => $request->student_id,
            'bus_type' => $request->bus_type,
            'bus_number' => $request->bus_number,
            'driver_name' => $request->driver_name,
            'driver_phonenumber' => $request->driver_phonenumber,
            'driver_image' => $paths,
            'conductor_name' => $request->conductor_name,
            'conductor_phonenumber' => $request->conductor_phonenumber,
            'conductor_image' => $pathss,
            'route' => $request->route,
            'ways' => $request->ways,
            'pickup_time' => $request->pickup_time,
            'dropoff_time' => $request->dropoff_time
        ]);

        return [
            "status" => 'true',
            "message" => 'Bus assigned to student',
            "data" => $bus
        ];
    }
}
