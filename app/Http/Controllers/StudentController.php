<?php

namespace App\Http\Controllers;

use App\Actions\GenerateAdmissionNumber;
use App\Models\Campus;
use App\Models\Result;
use App\Models\Pricing;
use App\Models\Schools;
use App\Models\Student;
use App\Enum\StudentStatus;
use Illuminate\Http\Request;
use App\Models\SchoolPayment;
use App\Traits\HttpResponses;
use App\Mail\StudentWelcomeMail;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    use HttpResponses;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = userAuth();
        $search = $request->query('search');
        $class = $request->query('class');
        $status = $request->query('status');

        $students = Student::where('sch_id', $user->sch_id)
            ->when($user->designation_id != 6, fn ($query) => $query->where('campus', $user->campus))
            ->when($class, fn($query) => $query->where('present_class', $class))
            ->when($status, fn($query) => $query->where('status', $status))
            ->when($search, fn($query) =>
                $query->whereAny(
                    ['firstname', 'surname', 'email_address', 'username', 'admission_number', 'present_class'],
                    'like',
                    "%{$search}%"
                )
            )
            ->latest()
            ->paginate(25);

        $studentCollection = StudentResource::collection($students);

        return $this->withPagination($studentCollection, 'Students List');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StudentRequest $request, GenerateAdmissionNumber $generateAdmissionNumber)
    {
        $user = Auth::user();

        $school = Schools::where('sch_id', $user->sch_id)
            ->first();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        if(! $school->auto_generate) {
            $request->validate([
                'admission_number' => ['required', 'string', 'max:255', Rule::unique('students', 'admission_number')],
            ]);
        }

        $studentsCount = Student::where('sch_id', $user->sch_id)
            ->where('status', StudentStatus::ACTIVE)
            ->count();

        $schoolPayment = SchoolPayment::where('sch_id', $user->sch_id)->first();
        $pricingId = $schoolPayment->pricing_id ?? Schools::where('sch_id', $user->sch_id)->value('pricing_id');
        $plan = Pricing::find($pricingId);

        if ($checkPlan = $this->handleCount($studentsCount, $plan)) {
            return $checkPlan;
        }

        $campus = Campus::where('sch_id', $user->sch_id)
            ->where('name', $request->campus)
            ->firstOrFail();

        if ($request->image) {
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);
            $imagePath = uploadImage($request->image, 'student', $cleanSchId);
        }

        $admissionNumber = $school->auto_generate
            ? $generateAdmissionNumber->handle($school->admission_number_initial)
            : $request->admission_number;

        $student = Student::create([
            'sch_id' => $school->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => '7',
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'admission_number' => $admissionNumber,
            'username' => $admissionNumber,
            'password' => bcrypt($request->password),
            'pass_word' => $request->password,
            'genotype' => $request->genotype,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nationality' => $request->nationality,
            'state' => $request->state,
            'session_admitted' => $request->session_admitted,
            'class' => $request->class,
            'present_class' => $request->present_class,
            'sub_class' => $request->sub_class,
            'image' => $imagePath['url'] ?? null,
            'home_address' => $request->home_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'file_id' => $imagePath['file_id'] ?? null,
            'status' => StudentStatus::ACTIVE,
            'is_preschool' => $campus->is_preschool,
            'created_by' => "{$user->surname} {$user->firstname} {$user->middlename}",
        ]);

        defer_email($request->email_address, new StudentWelcomeMail($student));

        return $this->success($student, 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Student $student)
    {
        $studentDetail = new StudentResource($student);
        return $this->success($studentDetail, 'Student Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Student $student)
    {
        $user = Auth::user();

        $school = Schools::where('sch_id', $user->sch_id)
            ->first();

        if (! $school) {
            return $this->error(null, 'School not found', 404);
        }

        $campus = Campus::where('sch_id', $user->sch_id)
            ->where('name', $request->campus)
            ->first();

        if (! $campus) {
            return $this->error(null, 'Campus not found', 404);
        }

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if ($request->image) {
            $imagePath = uploadImage($request->image, 'student', $cleanSchId, $user->file_id);
        }

        $student->update([
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'genotype' => $request->genotype,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nationality' => $request->nationality,
            'state' => $request->state,
            'session_admitted' => $request->session_admitted,
            'class' => $request->class,
            'present_class' => $request->present_class,
            'image' => $imagePath['url'] ?? $student->image,
            'home_address' => $request->home_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'is_preschool' => $campus->is_preschool,
            'file_id' => $imagePath['file_id'] ?? $student->file_id,
        ]);

        $studentFullname = trim("{$student->surname} {$student->firstname} {$student->middlename}");

        Result::where('student_id', $student->id)
            ->update(['student_fullname' => $studentFullname]);

        $studentDetail = new StudentResource($student);

        return $this->success($studentDetail, 'Student Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return $this->success(null, 'Student Deleted Successfully');
    }

    private function handleCount($count, $getplan)
    {
        if ($count >= 150 && $getplan->id == 1) {
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if ($count >= 350 && $getplan->id == 2) {
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if ($count >= 1000 && $getplan->id == 3) {
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }
    }
}
