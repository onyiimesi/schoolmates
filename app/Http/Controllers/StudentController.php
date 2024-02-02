<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Mail\StudentWelcomeMail;
use App\Models\Campus;
use App\Models\SchoolPayment;
use App\Models\Schools;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Traits\HttpResponses;
use ImageKit\ImageKit;

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
        ->where('campus', $user->campus)
        ->where('status', 'active')
        ->paginate(25);

        $studentCollection = StudentResource::collection($students);

        return [
            'status' => 'true',
            'message' => 'Students List',
            'data' => $studentCollection,
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'prev_page_url' => $students->previousPageUrl(),
                'next_page_url' => $students->nextPageUrl()
            ],
        ];

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentRequest $request)
    {
        $request->validated($request->all());

        $user = Auth::user();
        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        $getstudents = Student::where('sch_id', $user->sch_id)
        ->where('status', 'active')
        ->get();
        $count = $getstudents->count();

        $plan = SchoolPayment::where('sch_id', $user->sch_id)->first();
        if(!$plan){
            return "An error occured";
        }

        if($count >= 50 && $plan->pricing_id == 1){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if($count >= 250 && $plan->pricing_id == 2){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        if($count >= 500 && $plan->pricing_id == 3){
            return $this->error(null, "Maximum count reached. Upgrade account to continue", 400);
        }

        $campus = Campus::where('sch_id', $user->sch_id)
        ->where('name', $request->campus)
        ->first();

        $sch = Schools::where('sch_id', $user->sch_id)
        ->first();

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

        $student = Student::create([
            'sch_id' => $sch->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => '7',
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'admission_number' => $request->admission_number,
            'username' => $request->admission_number,
            'password' => Hash::make($request->password),
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
            'created_by' => $user->surname .' '. $user->firstname .' '. $user->middlename,
        ]);

        Mail::to($request->email_address)->send(new StudentWelcomeMail($student));

        return [
            "status" => 'true',
            "message" => 'Student Created Successfully',
            "data" => $student
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Student $student)
    {
        $students = new StudentResource($student);

        return [
            'status' => 'true',
            'message' => 'Student Details',
            'data' => $students
        ];
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
        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

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
            'file_id' => $fileId,
        ]);

        $students = new StudentResource($student);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $students
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
