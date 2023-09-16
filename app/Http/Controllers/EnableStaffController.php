<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnableStaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class EnableStaffController extends Controller
{
    use HttpResponses;

    public function enable(EnableStaffRequest $request, Staff $staff){

        $staff = Staff::find($request->id);

        if(!$staff){
            return $this->error('', 'Staff does not exist', 400);
        }

        $staff->update([
            'status' => 'active',
        ]);

        return [
            "status" => 'true',
            "message" => 'Enabled Successfully',
        ];


    }
}
