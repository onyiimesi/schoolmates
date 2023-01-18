<?php

namespace App\Http\Controllers;

use App\Http\Requests\StudentRequest;
use App\Http\Resources\StudentResource;
use App\Mail\StudentWelcomeMail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $student = StudentResource::collection(Student::where('status', 'active')->get());

        return [
            'status' => 'true',
            'message' => 'Students List',
            'data' => $student
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

        // if($request->image && $request->image->isValid()){
        //     $file_name = time().'.'.$request->image->extension();
        //     $request->image->move(public_path('images/students'), $file_name);
        //     $path = "public/images/students/$file_name";
        // }else{
        //     $path = "";
        // }

        $user = Auth::user();

        if($request->image){
            $file = $request->image;
            $folderName = 'https://schoolmate.powershellerp.com/public/students';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1); 
            $image = str_replace($replace, '', $file); 

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/students/'.$file_name, base64_decode($image));
            
            $paths = $folderName.'/'.$file_name;
        }else{
            $paths = "";
        }

        $student = Student::create([
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
            'class_sub_class' => $request->class_sub_class,
            'present_class' => $request->present_class,
            'sub_class' => $request->sub_class,
            'image' => $paths,
            'home_address' => $request->home_address,
            'phone_number' => $request->phone_number,
            'email_address' => $request->email_address,
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
        
        if($request->image){
            $file = $request->image;
            $folderName = 'https://schoolmate.powershellerp.com/public/students';
            
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            
            $replace = substr($file, 0, strpos($file, ',')+1); 
            $image = str_replace($replace, '', $file); 

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/students/'.$file_name, base64_decode($image));
            
            $paths = $folderName.'/'.$file_name;
        }
        
        $student->update([
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
