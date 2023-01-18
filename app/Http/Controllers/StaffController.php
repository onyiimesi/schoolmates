<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StaffRequest;
use App\Http\Resources\StaffsResource;
use App\Mail\StaffWelcomeMail;
use App\Models\Staff;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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

        $staff = StaffsResource::collection(Staff::where('status', 'active')->get());

        return [
            'status' => 'true',
            'message' => 'Staff List',
            'data' => $staff
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

        if($request->image){
            $file = $request->image;
            $folderName = 'http://127.0.0.1:8000/public/staffs';
            $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
            $replace = substr($file, 0, strpos($file, ',')+1); 
            $image = str_replace($replace, '', $file); 

            $image = str_replace(' ', '+', $image);
            $file_name = time().'.'.$extension;
            file_put_contents(public_path().'/staffs/'.$file_name, base64_decode($image));
            
            $paths = $folderName.'/'.$file_name;
        }else{
            $paths = "";
        }

        $staff = Staff::create([
            'designation_id' => $request->designation_id,
            'department' => $request->department,
            'surname' => $request->surname,
            'firstname' => $request->firstname,
            'middlename' => $request->middlename,
            'username' => $request->username,
            'email' => $request->email,
            'phoneno' => $request->phoneno,
            'address' => $request->address,
            'image' => $paths,
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
        $staff->update($request->all());

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
