<?php

namespace App\Http\Controllers;

use App\Http\Requests\DisableCampusRequest;
use App\Models\Campus;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;

class DisableCampusController extends Controller
{

    use HttpResponses;

    public function disable(DisableCampusRequest $request, Campus $campus){

        $campus = Campus::find($request->id);

        if(!$campus){
            return $this->error('', 'Campus does not exist', 400);
        }

        $campus->update([
            'status' => 'disabled',
        ]);

        return [
            "status" => 'true',
            "message" => 'Disabled Successfully',
        ];


    }
}
