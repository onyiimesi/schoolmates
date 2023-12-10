<?php

namespace App\Http\Controllers;

use App\Http\Requests\PreSchoolSubjectClassRequest;
use App\Http\Requests\PreSchoolSubjectRequest;
use App\Http\Resources\PreSchoolSubjectClassResource;
use App\Http\Resources\PreSchoolSubjectResource;
use App\Models\PreSchoolSubject;
use App\Models\PreSchoolSubjectClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreSchoolSubjectController extends Controller
{
    public function addSubject(PreSchoolSubjectRequest $request){

        $request->validated($request->all());

        $user = Auth::user();

        $subjects = PreSchoolSubject::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('subject', $request->subject)
        ->first();

        if(empty($subjects)){

            $presub = PreSchoolSubject::create([
                'sch_id' => $user->sch_id,
                'campus' => $user->campus,
                'period' => $request->period,
                'term' => $request->term,
                'session' => $request->session,
                'subject' => $request->subject,
                'topic' => $request->topic,
                'category' => $request->category,
            ]);

            return [
                'status' => '',
                'message' => 'Created Successfully',
                'data' => $presub
            ];

        }else if(!empty($subjects)){

            $subjects->update([
                'topic' => $request->topic
            ]);

            return [
                'status' => '',
                'message' => 'Created Successfully',
                'data' => $subjects
            ];
        }
    }

    public function getSubject(Request $request){

        $user = Auth::user();

        $subjects = PreSchoolSubjectResource::collection(PreSchoolSubject::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->get());

        return [
            'status' => 'success',
            'message' => '',
            'data' => $subjects
        ];

    }

    public function getSubjectID(Request $request){

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

    public function editSubject(Request $request){

        $subject = PreSchoolSubject::where('id', $request->id)->first();

        if(!$subject){
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

    public function addSubjectClass(PreSchoolSubjectClassRequest $request){

        $request->validated($request->all());

        $user = Auth::user();

        $pre = PreSchoolSubjectClass::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('class', $request->class)->first();

        if(empty($pre)){
            $presub = PreSchoolSubjectClass::create([
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

            return [
                'status' => '',
                'message' => 'Assigned Successfully',
                'data' => $presub
            ];

        }else if(!empty($pre)){

            $pre->update([
                'subjects' => $request->subjects
            ]);

            return [
                'status' => '',
                'message' => 'Updated Successfully',
                'data' => $pre
            ];
        }

    }

    public function getSubjectClass(Request $request){

        $user = Auth::user();

        $subjects = PreSchoolSubjectClassResource::collection(PreSchoolSubjectClass::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->get());

        return [
            'status' => 'success',
            'message' => '',
            'data' => $subjects
        ];

    }

    public function getSubjectByClass(Request $request){

        $user = Auth::user();

        $presubjects = PreSchoolSubjectClassResource::collection(PreSchoolSubjectClass::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('period', $request->period)
        ->where('term', $request->term)
        ->where('session', $request->session)
        ->where('class', $request->class)
        ->get());

        return [
            'status' => 'success',
            'message' => '',
            'data' => $presubjects
        ];

    }
}
