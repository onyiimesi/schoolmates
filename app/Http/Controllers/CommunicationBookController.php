<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommunicationBookRequest;
use App\Http\Resources\CommunicationBookResource;
use App\Models\AcademicPeriod;
use App\Models\CommunicationBook;
use App\Models\Designation;
use App\Models\Student;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommunicationBookController extends Controller
{
    use HttpResponses;

    /**
     * @return array<string, mixed>
     */
    public function communicate(CommunicationBookRequest $request): array|JsonResponse
    {
        $request->validated($request->all());

        /** @var Staff|Student $user */
        $user = Auth::user();

        $dsg = Designation::query()->find($user->designation_id);

        if (!$dsg) {
            return $this->error(null, 'Designation not found', 404);
        }

        $period = AcademicPeriod::query()
            ->where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->first();

        if (!$period) {
            return $this->error(null, 'Period not found', 404);
        }

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

    /**
     * @return array<string, mixed>|JsonResponse
     */
    public function getmessage(): array|JsonResponse
    {
        /** @var Staff $user */
        $user = Auth::user();

        if($user->designation_id == '7') {

            $student = Student::query()->find($user->id);

            if (! $student) {
                return $this->error(null, 'Student not found', 404);
            }

            $period = AcademicPeriod::query()
                ->where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->first();

            if (!$period) {
                return $this->error(null, 'Period not found', 404);
            }

            $msg = CommunicationBook::query()
                ->where('sch_id', $user->sch_id)
                ->where('campus', $student->campus)
                ->where('student_id', $student->id)
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
            return $this->error(null, "Can't perform this action", 403);
        }
    }
}
