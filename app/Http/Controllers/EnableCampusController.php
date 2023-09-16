<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnableCampusRequest;
use App\Models\Campus;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;

class EnableCampusController extends Controller
{
    use HttpResponses;

    public function enable(EnableCampusRequest $request, Campus $campus){

        $campus = Campus::find($request->id);

        if(!$campus){
            return $this->error('', 'Campus does not exist', 400);
        }

        $campus->update([
            'status' => 'active',
        ]);

        return [
            "status" => 'true',
            "message" => 'Enabled Successfully',
        ];


    }
}
