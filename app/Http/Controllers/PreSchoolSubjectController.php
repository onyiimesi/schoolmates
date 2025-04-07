<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSchoolSubjectClassRequest;
use App\Http\Requests\PreSchoolSubjectRequest;
use App\Http\Resources\PreSchoolSubjectClassResource;
use App\Http\Resources\PreSchoolSubjectResource;
use App\Models\PreSchoolSubject;
use App\Models\PreSchoolSubjectClass;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\ResponseCache\Facades\ResponseCache;

class PreSchoolSubjectController extends Controller
{
    use HttpResponses;

    public function addSubject(PreSchoolSubjectRequest $request)
    {
        $user = Auth::user();

        $subjects = PreSchoolSubject::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $request->period,
            'session' => $request->session,
            'subject' => $request->subject
        ])->first();

        if($subjects) {

            $subjects->update([
                'topic' => $request->topic
            ]);

            return $this->success(null, 'Updated Successfully');
        }

        PreSchoolSubject::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'subject' => $request->subject,
            'topic' => $request->topic,
            'category' => $request->category,
        ]);

        return $this->success(null, 'Created Successfully', 201);
    }

    public function getSubject(Request $request)
    {
        $user = Auth::user();

        $subjects = PreSchoolSubjectResource::collection(
            PreSchoolSubject::where([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session
            ])->get()
        );

        return $this->success($subjects, 'Subject list');
    }

    public function getSubjectID(Request $request)
    {
        $user = Auth::user();

        $subjects = PreSchoolSubjectResource::collection(PreSchoolSubject::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $request->id)
            ->get());

        return [
            'status' => 'success',
            'message' => '',
            'data' => $subjects
        ];
    }

    public function editSubject(Request $request)
    {
        $subject = PreSchoolSubject::where('id', $request->id)->first();

        if (!$subject) {
            return response(null, 404);
        }

        $subject->update([
            'subject' => $request->subject,
            'category' => $request->category,
            'topic' => $request->topic
        ]);

        return [
            'status' => 'success',
            'message' => 'Edited Successfully',
            'data' => $subject
        ];
    }

    public function deleteSubject(Request $request)
    {
        $user = Auth::user();

        $subject = PreSchoolSubject::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('id', $request->id)->first();

        $subject->delete();

        return response(null, 204);
    }

    public function addSubjectClass(PreSchoolSubjectClassRequest $request)
    {
        $user = Auth::user();

        $existingSubjectClass = PreSchoolSubjectClass::where([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'class' => $request->class
        ])->first();

        ResponseCache::clear();

        if ($existingSubjectClass) {
            $existingSubjectClass->update([
                'subjects' => $request->subjects
            ]);

            return $this->success(null, 'Subjects updated successfully.');
        }

        PreSchoolSubjectClass::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'period' => $request->period,
            'term' => $request->term,
            'session' => $request->session,
            'class_id' => $request->class_id,
            'class' => $request->class,
            'category' => $request->category,
            'subjects' => $request->subjects
        ]);

        return $this->success(null, 'Subjects assigned successfully.', 201);
    }

    public function getSubjectClass(Request $request)
    {
        $user = Auth::user();

        $subjects = PreSchoolSubjectClassResource::collection(PreSchoolSubjectClass::where('sch_id', $user->sch_id)
            ->where('campus', $user->campus)
            ->where('period', $request->period)
            ->where('term', $request->term)
            ->where('session', $request->session)
            ->get());

        return $this->success($subjects, 'Subject list');
    }

    public function getSubjectByClass(Request $request)
    {
        $user = Auth::user();

        $subjects = PreSchoolSubjectClass::where([
            ['sch_id', $user->sch_id],
            ['campus', $user->campus],
            ['period', $request->period],
            ['term', $request->term],
            ['session', $request->session],
            ['class', $request->class],
        ])->get();

        $presubjects = PreSchoolSubjectClassResource::collection($subjects);

        return $this->success($presubjects, 'Subject list');
    }
}
