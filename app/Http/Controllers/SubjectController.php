<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Http\Resources\SubjectResource;
use App\Models\StudentScore;
use App\Models\Subject;
use App\Models\SubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
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

        $data = SubjectResource::collection(
            Subject::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->get()
        );

        return $this->success($data, 'Subjects');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SubjectRequest $request)
    {
        $user = Auth::user();

        $data = Subject::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'subject' => $request->subject,
        ]);

        return $this->success($data, 'Subject created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Subject $subject)
    {
        $subjects = new SubjectResource($subject);

        return $this->success($subjects, 'Subjects');
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subject $subject)
    {
        $user = Auth::user();

        $data = DB::transaction(function() use($user, $request, $subject) {
            SubjectClass::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('subject', $subject->subject)
                ->update(['subject' => $request->subject]);

            StudentScore::where('subject', $subject->subject)
                ->whereHas('result', function ($query) use ($user) {
                    $query->where('sch_id', $user->sch_id)
                        ->where('campus', $user->campus);
                })
                ->update(['subject' => $request->subject]);

            $subject->update([
                'subject' => $request->subject,
            ]);

            return new SubjectResource($subject);
        });

        return $this->success($data, 'Subject updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subject $subject)
    {
        $user = Auth::user();

        return DB::transaction(function () use($user, $subject) {
            SubjectClass::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('subject', $subject->subject)
                ->delete();

            StudentScore::where('subject', $subject->subject)
            ->whereHas('result', function ($query) use ($user) {
                $query->where('sch_id', $user->sch_id)
                    ->where('campus', $user->campus);
            })->delete();

            $subject->delete();

            return response(null, 204);
        });
    }
}
