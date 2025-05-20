<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Mail\StudentWelcomeMail;
use App\Models\Campus;
use App\Models\Pricing;
use App\Models\Result;
use App\Models\SchoolPayment;
use App\Models\Schools;
use App\Models\Student;
use App\Services\AdmissionNumberService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;

class StudentController extends Controller
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

        $students = Student::where('sch_id', $user->sch_id)
            ->when($user->designation_id != 6, fn($query) => $query->where('campus', $user->campus))
            ->paginate(25);

        $studentCollection = StudentResource::collection($students);

        return $this->withPagination($studentCollection, 'Students List');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request, AdmissionNumberService $admissionNumberService)
    {
        $user = Auth::user();

        $sch = Schools::where('sch_id', $user->sch_id)
            ->first();

        if (!$sch) {
            return $this->error(null, 'School not found', 404);
        }

        if(!$sch->auto_generate) {
            $request->validate([
                'admission_number' => ['required', 'string', 'max:255', 'unique:students'],
            ]);
        }

        $imageKit = getImageKit();

        $getstudents = Student::where('sch_id', $user->sch_id)
        ->where('status', 'active')
        ->get();
        $count = $getstudents->count();

        $plan = SchoolPayment::where('sch_id', $user->sch_id)->first();

        if($plan){
            $getplan = Pricing::where('id', $plan->pricing_id)->first();
        } else {
            $school = Schools::where('sch_id', $user->sch_id)->first();
            $getplan = Pricing::where('id', $school->pricing_id)->first();
        }

        $this->handleCount($count, $getplan);

        $campus = Campus::where('sch_id', $user->sch_id)
        ->where('name', $request->campus)
        ->firstOrFail();

        if($request->image){
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

            $file = $request->image;
            $baseFolder = 'student';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);
            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            $folderPath = $file_name;

            $folderName = $baseFolder . '/' . $cleanSchId;

            $uploadFile = $imageKit->uploadFile([
                'file' => $file,
                'fileName' => $folderPath,
                'folder' => $folderName
            ]);

            $url = $uploadFile->result->url;
            $fileId = $uploadFile->result->fileId;

            $paths = $url;
        }else{
            $paths = "";
            $fileId = "";
        }

        $admissionNumber = $sch->auto_generate
            ? $admissionNumberService->generateUniqueAdmissionNumber($sch->admission_number_initial)
            : $request->admission_number;

        $student = Student::create([
            'sch_id' => $sch->sch_id,
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
            'image' => $paths,
            'home_address' => $request->home_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'file_id' => $fileId,
            'status' => 'active',
            'is_preschool' => $campus->is_preschool,
            'created_by' => $user->surname .' '. $user->firstname .' '. $user->middlename,
        ]);

        defer_email($request->email_address, new StudentWelcomeMail($student));

        return $this->success($student, 'Student Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Student $student)
    {
        $user = Auth::user();

        $imageKit = getImageKit();

        $campus = Campus::where('sch_id', $user->sch_id)
        ->where('name', $request->campus)
        ->first();

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if($request->image){
            $file = $request->image;
            $baseFolder = 'student';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);
            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            $folderPath = $file_name;
            $folderName = $baseFolder . '/' . $cleanSchId;

            $fileId = $user->file_id;
            $imageKit->deleteFile($fileId);

            $uploadFile = $imageKit->uploadFile([
                'file' => $file,
                'fileName' => $folderPath,
                'folder' => $folderName
            ]);

            $url = $uploadFile->result->url;
            $fileId = $uploadFile->result->fileId;
            $paths = $url;
        }else{
            $paths = "";
            $fileId = "";
        }

        $student->update([
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'admission_number' => $request->admission_number,
            'username' => $request->admission_number,
            'genotype' => $request->genotype,
            'blood_group' => $request->blood_group,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'nationality' => $request->nationality,
            'state' => $request->state,
            'session_admitted' => $request->session_admitted,
            'class' => $request->class,
            'present_class' => $request->present_class,
            'image' => $paths,
            'home_address' => $request->home_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
            'is_preschool' => $campus->is_preschool,
            'file_id' => $fileId,
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        $student->delete();

        return $this->success(null, 'Student Deleted Successfully');
    }

    private function handleCount($count, $getplan)
    {
        if($count >= 50 && $getplan->id == 1){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if($count >= 250 && $getplan->id == 2){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if($count >= 500 && $getplan->id == 3){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }
    }
}
