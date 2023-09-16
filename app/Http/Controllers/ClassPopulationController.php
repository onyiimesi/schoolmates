<?php

namespace App\Http\Controllers;

use App\Models\AcademicPeriod;
use App\Models\ClassModel;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassPopulationController extends Controller
{
    public function getclasspopulation()
    {

        $staff = Auth::user();
        $period = AcademicPeriod::first();

        $class = Student::where('present_class', $staff->class_assigned)
        ->where('sch_id', $staff->sch_id)
        ->where('campus', $staff->campus)
        ->where("session_admitted", $period->session)->get();

        $popu = $class->count();


        return [
            'status' => 'true',
            'message' => 'Class Population',
            'data' => $popu
        ];
    }

    public function getallpopulation()
    {

        $all = Student::get();

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Student Population',
            'data' => $popul
        ];
    }

    public function getstaffpopulation()
    {

        $all = Staff::get();

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Staff Population',
            'data' => $popul
        ];
    }

    public function getteacherpopulation()
    {

        $all = Staff::where('designation_id', '4');

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Teacher Population',
            'data' => $popul
        ];
    }

    public function getschoolpopulation()
    {

        $staff = Staff::get();

        $student = Student::get();

        $total = $staff->count() + $student->count();

        return [
            'status' => 'true',
            'message' => 'Total School Population',
            'data' => $total
        ];
    }
}
