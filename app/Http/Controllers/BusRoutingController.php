<?php

namespace App\Http\Controllers;

use App\Http\Requests\BusRoutingRequest;
use App\Models\AcademicPeriod;
use App\Models\BusRouting;
use App\Models\Schools;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Staff;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;

class BusRoutingController extends Controller
{
    use HttpResponses;

    /**
     * @return JsonResponse
     */
    public function route(BusRoutingRequest $request): JsonResponse
    {
        /** @var Staff|Student $user */
        $user = Auth::user();

        $request->validated($request->all());

        $sch = Schools::where('sch_id', $user->sch_id)->first();

        if (! $sch instanceof Schools) {
            return $this->error(null, "School not found", 404);
        }

        $period = AcademicPeriod::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if (! $period instanceof AcademicPeriod) {
            return $this->error(null, "Academic period not found", 404);
        }

        $paths = "";
        if($request->driver_image){
            $file = (string) $request->driver_image;
            $folderName = 'https://schoolmate.powershellerp.com/public/routes/drivers';
            preg_match('/^data:(.*)\/(.*);base64,/', $file, $matches);

            if (!isset($matches[2])) {
                throw new \InvalidArgumentException('Invalid base64 file format.');
            }

            $extension = $matches[2];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/routes/drivers/'.$file_name, base64_decode($image));

            $paths = $folderName.'/'.$file_name;
        }

        $pathss = "";
        if($request->conductor_image){
            $file = (string) $request->conductor_image;
            $folderName = 'https://schoolmate.powershellerp.com/public/routes/conductors';
            preg_match('/^data:(.*)\/(.*);base64,/', $file, $matches);

            if (!isset($matches[2])) {
                throw new \InvalidArgumentException('Invalid base64 file format.');
            }

            $extension = $matches[2];
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

        return $this->success($bus, 'Bus assigned to student', 201);
    }
}
