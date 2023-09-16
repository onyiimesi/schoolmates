<?php

namespace App\Http\Controllers;

use App\Http\Resources\SchoolsResource;
use App\Models\Schools;
use Illuminate\Http\Request;

class SchoolsController extends Controller
{
    public function schools(){

        $school = SchoolsResource::collection(Schools::get());

        return [
            'status' => 'true',
            'message' => 'School Details',
            'data' => $school
        ];

    }

    
}
