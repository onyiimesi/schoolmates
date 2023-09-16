<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommunicationBookRequest;
use App\Http\Resources\CommunicationBookResource;
use App\Models\AcademicPeriod;
use App\Models\CommunicationBook;
use App\Models\Designation;
use App\Models\Student;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationBookController extends Controller
{
    use HttpResponses;

    public function communicate(CommunicationBookRequest $request){

        $request->validated($request->all());

        $user = Auth::user();
        $dsg = Designation::find($user->designation_id);
        $period = AcademicPeriod::first();
        $stat = 'Pending';

        $comm = CommunicationBook::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $period->period,
            'term' => $period->term,
            'session' => $period->session,
            'title' => $request->title,
            'urgency' => $request->urgency,
            'student_id' => $request->student_id,
            'admission_number' => $request->admission_number,
            'message' => $request->message,
            'sender' => $dsg->designation_name,
            'status' => $stat,
        ]);

        return [
            "status" => 'true',
            "message" => 'Sent Successfully',
            "data" => $comm
        ];

    }

    public function getmessage(){

        $stud = Auth::user();

        if($stud->designation_id == '5'){

            $student = Student::find($stud->id);
            $period = AcademicPeriod::first();

            $msg = CommunicationBook::where('student_id', $student->id)
            ->where('period', $period->period)
            ->where('term', $period->term)
            ->where('session', $period->session)
            ->get();

            $msgs = CommunicationBookResource::collection($msg);

            return [
                "status" => 'true',
                "message" => 'Message',
                "data" => $msgs
            ];

        }else {
            return $this->error('', "Can't perform this action", 401);
        }



    }
}
