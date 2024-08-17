<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StaffRequest;
use App\Http\Resources\StaffsResource;
use App\Mail\StaffWelcomeMail;
use App\Models\Campus;
use App\Models\Schools;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use ImageKit\ImageKit;

class StaffController extends Controller
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

        if ($user->designation_id == 6) {
            $staff = Staff::where('sch_id', $user->sch_id)
                ->where('status', 'active')
                ->paginate(25);
        } else {
            $staff = Staff::where('sch_id', $user->sch_id)
                ->where('campus', $user->campus)
                ->where('status', 'active')
                ->paginate(25);
        }

        $staffCollection = StaffsResource::collection($staff);

        return [
            'status' => true,
            'message' => 'Staff List',
            'data' => $staffCollection,
            'pagination' => [
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'per_page' => $staff->perPage(),
                'prev_page_url' => $staff->previousPageUrl(),
                'next_page_url' => $staff->nextPageUrl(),
            ],
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StaffRequest $request)
    {
        $request->validated($request->all());
        $user = Auth::user();

        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        $campus = Campus::where('sch_id', $user->sch_id)
        ->where('name', $request->campus)
        ->first();

        $sch = Schools::where('sch_id', $user->sch_id)
        ->first();

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if($request->image){

            $file = $request->image;
            $baseFolder = 'staff';
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

            // $userFolder = $cleanSchId;
            // $folderPath = public_path($baseFolder . '/' . $userFolder);
            // $folderName = env('STAFF_FOLDER_NAME') . '/' . $cleanSchId;
            // if (!file_exists(public_path($baseFolder))) {
            //     mkdir(public_path($baseFolder), 0777, true);
            // }
            // if (!file_exists($folderPath)) {
            //     mkdir($folderPath, 0777, true);
            // }
            // file_put_contents($folderPath.'/'.$file_name, base64_decode($image));

            $paths = $url;
        }else{
            $paths = "";
            $fileId = "";
        }

        if($request->signature){

            $file = $request->signature;
            $baseFolder = 'signature';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $sig = str_replace($replace, '', $file);
            $sig = str_replace(' ', '+', $sig);
            $file_name = uniqid().'.'.$extension;
            $folderPath = $file_name;

            $folderName = $baseFolder . '/' . $cleanSchId;

            $uploadFile = $imageKit->uploadFile([
                'file' => $file,
                'fileName' => $folderPath,
                'folder' => $folderName
            ]);

            $url = $uploadFile->result->url;
            $sigId = $uploadFile->result->fileId;

            // $file = $request->signature;
            // $baseFolder = 'staffs/signature';
            // $userFolder = $cleanSchId;
            // $folderPath = public_path($baseFolder . '/' . $userFolder);
            // $folderName = env('SIGNATURE_FOLDER_NAME') . '/' . $cleanSchId;
            // $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            // $replace = substr($file, 0, strpos($file, ',')+1);
            // $sig = str_replace($replace, '', $file);

            // $sig = str_replace(' ', '+', $sig);
            // $file_name = time().'.'.$extension;

            // if (!file_exists(public_path($baseFolder))) {
            //     mkdir(public_path($baseFolder), 0777, true);
            // }

            // if (!file_exists($folderPath)) {
            //     mkdir($folderPath, 0777, true);
            // }

            // file_put_contents($folderPath.'/'.$file_name, base64_decode($sig));

            $pathss = $url;
        }else{
            $pathss = "";
            $sigId = "";
        }

        if($request->teacher_type == ""){
            $type = "";
        }else{
            $type = $request->teacher_type;
        }

        $staff = Staff::create([
            'sch_id' => $sch->sch_id,
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $request->username,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'class_assigned' => $request->class_assigned,
            'image' => $paths,
            'signature' => $pathss,
            'teacher_type' => $type,
            'is_preschool' => $campus->is_preschool,
            'file_id' => $fileId,
            'sig_id' => $sigId,
            'password' => Hash::make($request->password),
            'pass_word' => $request->password,
            'status' => 'active'
        ]);

        Mail::to($request->email)->send(new StaffWelcomeMail($staff));

        return [
            "status" => 'true',
            "message" => 'Staff Created Successfully',
            "data" => $staff
        ];

        // return new StaffsResource($staff);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {

        // if($this->isNotAuthorized($staff)){

        //     return $this->isNotAuthorized($staff);

        // }else{

        //     $staffs = new StaffsResource($staff);

        //     return [
        //         'status' => 'true',
        //         'message' => 'Staff Details',
        //         'data' => $staffs
        //     ];
        // }

        $staffs = new StaffsResource($staff);

        return [
            'status' => 'true',
            'message' => 'Staff Details',
            'data' => $staffs
        ];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Staff $staff)
    {
        $user = Auth::user();
        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $user->sch_id);

        if($request->image){
            $file = $request->image;
            $baseFolder = 'staff';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $image = str_replace($replace, '', $file);
            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            $folderPath = $file_name;
            $folderName = $baseFolder . '/' . $cleanSchId;

            $fileId = $staff->file_id;
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
            $paths = $staff->image;
            $fileId = "";
        }

        if($request->signature){
            $file = $request->signature;
            $baseFolder = 'signature';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1);
            $sig = str_replace($replace, '', $file);
            $sig = str_replace(' ', '+', $sig);
            $file_name = uniqid().'.'.$extension;
            $folderPath = $file_name;
            $folderName = $baseFolder . '/' . $cleanSchId;

            $sigId = $staff->sig_id;
            $imageKit->deleteFile($sigId);

            $uploadFile = $imageKit->uploadFile([
                'file' => $file,
                'fileName' => $folderPath,
                'folder' => $folderName
            ]);

            $url = $uploadFile->result->url;
            $sigId = $uploadFile->result->fileId;
            $pathss = $url;
        }else{
            $pathss = $staff->signature;
            $sigId = "";
        }

        if($request->teacher_type == ""){
            $type = $staff->teacher_type;
        }else{
            $type = $request->teacher_type;
        }

        $campus = Campus::where('name', $request->campus)->first();

        $staff->update([
            'campus' => $request->campus,
            'campus_type' => $campus->campus_type,
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $request->username,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'class_assigned' => $request->class_assigned,
            'is_preschool' => $campus->is_preschool,
            'image' => $paths,
            'signature' => $pathss,
            'teacher_type' => $type,
            'file_id' => $fileId,
            'sig_id' => $sigId
        ]);

        $staffs = new StaffsResource($staff);

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $staffs
        ];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();

        return response(null, 204);
    }

    private function isNotAuthorized($staff){
        if(Auth::user()->id !== $staff->user_id){
            return $this->error('', 'You are not authorized to make this request', 403);
        }
    }

}
