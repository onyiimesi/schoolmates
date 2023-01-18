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

        }else{

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
        
        $stud->update($request->all());

        return [
            "status" => 'true',
            "message" => 'Updated Successfully',
            "data" => $stud
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
