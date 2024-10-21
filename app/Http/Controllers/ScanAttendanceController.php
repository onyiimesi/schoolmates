<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\Staff;
use App\Models\StaffScanAttendance;
use App\Traits\HttpResponses;
use GeoIp2\Database\Reader;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;

class ScanAttendanceController extends Controller
{
    use HttpResponses;

    public function staffAttendance(Request $request)
    {
        try {
            $agent = new Agent();
            $ipAddress = $request->ip();
        
            if ($ipAddress == '127.0.0.1') {
                $ipAddress = '8.8.8.8';
            }

            $client = new Client();
            $response = $client->get("http://ip-api.com/json/{$ipAddress}");
            $locationData = (object)json_decode($response->getBody(), true);

            //$address = getLocation($locationData->lat, $locationData->lon);

            $user = Auth::user();
            $staff = Staff::findOrFail($user->id);
            $academicPeriod = AcademicPeriod::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->firstOrFail();


            DB::beginTransaction();

            $staff->staffScanAttendances()->create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $academicPeriod->period,
                'term' => $academicPeriod->term,
                'session' => $academicPeriod->session,
                'time_in' => now()->format('h:i:s'),
                'date_in' => now()->format('Y-m-d'),
                'ip_address' => $request->ip(),
                'device' => $agent->device(),
                'os' => $agent->platform(),
                'address' => null,
                'location' => $locationData,
                'status' => 'success',
            ]);

            DB::commit();
            return $this->success(null, 'Successful!');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
