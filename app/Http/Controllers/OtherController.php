<?php

namespace App\Http\Controllers;

use App\Http\Resources\DesignationResource;
use App\Http\Resources\StaffsResource;
use App\Models\AcademicPeriod;
use App\Models\Designation;
use App\Models\ExtraCurricular;
use App\Models\Payment;
use App\Models\PreSchoolExtraCurricular;
use App\Models\Schools;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OtherController extends Controller
{
    use HttpResponses;

    public function extra(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        ExtraCurricular::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name
        ]);

        return $this->success(null, "Created Successfully", 201);

    }

    public function getextra() : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = ExtraCurricular::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get();

        return response()->json([
            'status' => 'true',
            'message' => 'List',
            'data' => $extra->map(function($name) {
                return [
                    "id" => $name->id,
                    "name" => $name->name
                ];
            })->toArray()
        ]);
    }

    public function delextra(Request $request) : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = ExtraCurricular::findorFail($request->id);

        $extra->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'Deleted Successfully'
        ]);
    }

    public function role()
    {
        $user = Auth::user();

        if($user->designation_id === 6){

            $school = Schools::where('sch_id', $user->sch_id)->first();

            if($school->pricing_id == 1 || $school->pricing_id == 3){
                $roleNot = ['2' ,'6', '7'];
            }else{
                $roleNot = ['6', '7'];
            }

            return DesignationResource::collection(
                Designation::whereNotIn('id', $roleNot)->get()
            );

        } elseif($user->designation_id === 1){

            $school = Schools::where('sch_id', $user->sch_id)->first();

            if($school->pricing_id == 1 || $school->pricing_id == 2){
                $roleNot = ['1', '2' ,'6', '7'];
            }else{
                $roleNot = ['1', '6', '7'];
            }

            return DesignationResource::collection(Designation::whereNotIn('id', $roleNot)->get());

        }
    }

    public function preextra(Request $request): JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        PreSchoolExtraCurricular::create([
            'sch_id' => $user->sch_id,
            'campus' => $user->campus,
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'true',
            'message' => 'Created Successfully',
        ]);

    }

    public function pregetextra() : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = PreSchoolExtraCurricular::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->get();

        return response()->json([
            'status' => 'true',
            'message' => 'List',
            'data' => $extra->map(function($name) {
                return [
                    "id" => $name->id,
                    "name" => $name->name
                ];
            })->toArray()
        ]);
    }

    public function predelextra(Request $request) : JsonResponse
    {
        $user = Auth::user();

        if(!$user){
            return $this->error('', 'Unauthenticated', 401);
        }

        $extra = PreSchoolExtraCurricular::findorFail($request->id);

        $extra->delete();

        return response()->json([
            'status' => 'true',
            'message' => 'Deleted Successfully'
        ]);
    }

    public function paymentinvoice($id)
    {
        $user = Auth::user();
        $period = AcademicPeriod::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->first();

        $pay = Payment::where('invoice_id', $id)
        ->where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where('term', $period->term)
        ->where('session', $period->session)
        ->with('invoice')
        ->get();

        $group = $pay->groupBy(['student_id']);
        $data = $group->map(function ($students, $studentId) {
            $name = $students->first();
            return [
                'sch_id' => $name->sch_id,
                'campus' => $name->campus,
                'term' => $name->term,
                'session' => $name->session,
                'student_id' => $studentId,
                'class_name' => $name->invoice->class,
                'student_fullname' => $name->student_fullname,
                'payment' => $students->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'invoice_id' => $payment->invoice_id,
                        'bank_name' => $payment->bank_name,
                        'account_name' => $payment->account_name,
                        'payment_method' => $payment->payment_method,
                        'amount_paid' => $payment->amount_paid,
                        'total_amount' => $payment->total_amount,
                        'amount_due' => $payment->amount_due,
                        'type' => $payment->type,
                        'status' => $payment->status,
                        'paid_at' => Carbon::parse($payment->created_at)->format('j M Y')
                    ];
                })->toArray()
            ];
        })->values()->toArray();

        if($data){
            return response()->json([
                'status' => "true",
                'message' => "Payment by invoice ID",
                'data' => $data,
            ], 200);
        }

        return $this->success([], "Payment by invoice ID", 200);
    }

    public function staffByClass($class)
    {
        $user = Auth::user();

        $staffs = Staff::where('sch_id', $user->sch_id)
        ->where('campus', $user->campus)
        ->where("class_assigned", $class)
        ->get();

        $data = StaffsResource::collection($staffs);

        return $this->success($data, "List staffs by class");
    }

    public function storageLink()
    {
        if (!File::exists(public_path('storage'))) {
            File::link(storage_path('app/public'), public_path('storage'));
            return $this->success(null, 'Storage link created successfully.');
        } else {
            return $this->error(null, 'Storage link already exists.', 404);
        }

    }

    public function admissionNumberSettings(Request $request)
    {
        $request->validate([
            'sch_id' => 'required|exists:schools,sch_id',
            'auto_generate' => 'required|boolean',
            'initial' => 'nullable|string|max:5',
        ]);

        if($request->auto_generate) {
            $request->validate([
                'initial' => 'required|string|max:5',
            ]);
        }

        $school = Schools::where('sch_id', $request->sch_id)
            ->first();

        if(! $school) {
            return $this->error(null, 'School not found');
        }

        $autoGenerate = $request->auto_generate ? 1 : 0;
        $initial = $autoGenerate ? $request->initial : null;

        $school->update([
            'auto_generate' => $autoGenerate,
            'admission_number_initial' => $initial,
        ]);

        return $this->success(null, "Successful");
    }

    public function getAdmissionNumberSettings($schId)
    {
        $school = Schools::select('id', 'sch_id', 'auto_generate', 'admission_number_initial')->where('sch_id', $schId)
            ->first();

        if(! $school) {
            return $this->error(null, 'School not found');
        }

        return $this->success($school, "Admission number settings");
    }
}
