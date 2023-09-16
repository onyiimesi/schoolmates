<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisableStaffRequest;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class DisableStaffController extends Controller
{
    use HttpResponses;

    public function disable(DisableStaffRequest $request, Staff $staff){

        $staff = Staff::find($request->id);

        if(!$staff){
            return $this->error('', 'Staff does not exist', 400);
        }

        $staff->update([
            'status' => 'disabled',
        ]);

        return [
            "status" => 'true',
            "message" => 'Disabled Successfully',
        ];


    }
}
