<?php

namespace App\Http\Controllers;

use App\Http\Resources\LoginResource;
use App\Http\Resources\StaffsResource;
use App\Http\Resources\StudentResource;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Auth\Events\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ImageKit\ImageKit;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $stud = Auth::user();

        if($stud->designation_id){

            $staffs = new StaffsResource($stud);

            return [
                'status' => 'true',
                'message' => 'Profile Details',
                'data' => $staffs
            ];

        }else if($stud->designation_id == '7'){

            $studs = new StudentResource($stud);

            return [
                'status' => 'true',
                'message' => 'Profile Details',
                'data' => $studs
            ];
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $stud = $request->user();
        $imageKit = new ImageKit(
            env('IMAGEKIT_PUBLIC_KEY'),
            env('IMAGEKIT_PRIVATE_KEY'),
            env('IMAGEKIT_URL_ENDPOINT')
        );

        $paths = $stud->image;
        $pathss = $stud->signature;

        if($stud->designation_id != '7'){
            // $stud->update($request->all());
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $stud->sch_id);

            if($request->image){
                $fileId = "";
                $file = $request->image;
                $baseFolder = 'staff';
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1);
                $image = str_replace($replace, '', $file);
                $image = str_replace(' ', '+', $image);
                $file_name = time().'.'.$extension;
                $folderPath = $file_name;
                $folderName = $baseFolder . '/' . $cleanSchId;

                $fileId = $stud->file_id;
                $del = $imageKit->deleteFile($fileId);

                if($del){
                    $uploadFile = $imageKit->uploadFile([
                        'file' => $file,
                        'fileName' => $folderPath,
                        'folder' => $folderName
                    ]);
                }

                $url = $uploadFile->result->url;
                $paths = $url;
                $fileId = $uploadFile->result->fileId;

            }else{
                $paths = $stud->image;
                $fileId = "";
            }

            if($request->signature){
                $sigId = "";
                $file = $request->signature;
                $baseFolder = 'signature';
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1);
                $sig = str_replace($replace, '', $file);
                $sig = str_replace(' ', '+', $sig);
                $file_name = uniqid().'.'.$extension;
                $folderPath = $file_name;
                $folderName = $baseFolder . '/' . $cleanSchId;

                $sigId = $stud->sig_id;
                $imageKit->deleteFile($sigId);

                $uploadFile = $imageKit->uploadFile([
                    'file' => $file,
                    'fileName' => $folderPath,
                    'folder' => $folderName
                ]);

                $url = $uploadFile->result->url;
                $pathss = $url;
                $sigId = $uploadFile->result->fileId;

            }else{
                $pathss = "";
                $sigId = "";
            }

            $stud->update([
                'department' => $request->department,
                'surname' => $request->surname,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'username' => $request->username,
                'email' => $request->email,
                'phoneno' => $request->phoneno,
                'address' => $request->address,
                'image' => $paths,
                'signature' => $pathss,
                'file_id' => $fileId,
                'sig_id' => $sigId
            ]);

            return [
                "status" => 'true',
                "message" => 'Updated Successfully',
                "data" => $stud
            ];

        }else if($stud->designation_id == '7'){
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $stud->sch_id);

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

                $fileId = $stud->file_id;
                $imageKit->deleteFile($fileId);

                $uploadFile = $imageKit->uploadFile([
                    'file' => $file,
                    'fileName' => $folderPath,
                    'folder' => $folderName
                ]);

                $url = $uploadFile->result->url;
                $paths = $url;
                $fileId = $uploadFile->result->fileId;
            }else{
                $fileId = "";
            }

            $stud->update([
                'department' => $request->department,
                'surname' => $request->surname,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'username' => $request->username,
                'email' => $request->email,
                'phoneno' => $request->phoneno,
                'address' => $request->address,
                'image' => $paths,
                'file_id' => $fileId,
            ]);

            return [
                "status" => 'true',
                "message" => 'Updated Successfully',
                "data" => $stud
            ];

        }

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
