<?php

namespace App\Http\Controllers;

use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class StudentImportController extends Controller
{
    public function import(Request $request){

        $file = $request->input('files');

        $extension = explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $replace = substr($file, 0, strpos($file, ',')+1);
        $image = str_replace($replace, '', $file);

        $image = str_replace(' ', '+', $image);
        $file_name = time().'.'.$extension;
        file_put_contents($file_name, base64_decode($image));

        Excel::import(new StudentsImport, $file_name);

        return [
            "status" => 'true',
            "message" => 'Imported Successfully',
        ];
    }
}
