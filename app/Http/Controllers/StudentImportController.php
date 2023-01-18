<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentImportController extends Controller
{
    public function import(Request $request){

        Excel::import(new StudentsImport, $request->file);

        return [
            "status" => 'true',
            "message" => 'Imported Successfully',
        ];
    }
}
