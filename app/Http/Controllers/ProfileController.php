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
        
        $paths = $stud->image;
        $pathss = $stud->signature;
        
        if($stud->designation_id != '7'){
            // $stud->update($request->all());
            $cleanSchId = preg_replace("/[^a-zA-Z0-9]/", "", $stud->sch_id);
            
            if($request->image){
                // unlink($prev);
                $file = $request->image;
                $baseFolder = 'staffs';
                $userFolder = $cleanSchId;
                $folderPath = public_path($baseFolder . '/' . $userFolder);
                $folderName = env('STAFF_FOLDER_NAME') . '/' . $cleanSchId;
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                
                $replace = substr($file, 0, strpos($file, ',')+1); 
                $image = str_replace($replace, '', $file); 
    
                $image = str_replace(' ', '+', $image);
                $file_name = time().'.'.$extension;

                if (!file_exists(public_path($baseFolder))) {
                    mkdir(public_path($baseFolder), 0777, true);
                }
    
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath.'/'.$file_name, base64_decode($image));
                
                $paths = $folderName.'/'.$file_name;
                
            }
            
            if($request->signature){
                $file = $request->signature;
                $baseFolder = 'staffs/signature';
                $userFolder = $cleanSchId;
                $folderPath = public_path($baseFolder . '/' . $userFolder);
                $folderName = env('SIGNATURE_FOLDER_NAME') . '/' . $cleanSchId;
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1); 
                $sig = str_replace($replace, '', $file); 
    
                $sig = str_replace(' ', '+', $sig);
                $file_name = time().'.'.$extension;

                if (!file_exists(public_path($baseFolder))) {
                    mkdir(public_path($baseFolder), 0777, true);
                }
    
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }

                file_put_contents($folderPath.'/'.$file_name, base64_decode($sig));
                
                $pathss = $folderName.'/'.$file_name;
            }else{
                $pathss = "";
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
                'signature' => $pathss
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
                $baseFolder = 'students';
                $userFolder = $cleanSchId;
                $folderPath = public_path($baseFolder . '/' . $userFolder);
                $folderName = env('STUDENT_FOLDER_NAME') . '/' . $cleanSchId;
                $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
                $replace = substr($file, 0, strpos($file, ',')+1); 
                $image = str_replace($replace, '', $file); 
    
                $image = str_replace(' ', '+', $image);
                $file_name = time().'.'.$extension;

                if (!file_exists(public_path($baseFolder))) {
                    mkdir(public_path($baseFolder), 0777, true);
                }
    
                if (!file_exists($folderPath)) {
                    mkdir($folderPath, 0777, true);
                }
                
                file_put_contents($folderPath.'/'.$file_name, base64_decode($image));
                
                $paths = $folderName.'/'.$file_name;
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
