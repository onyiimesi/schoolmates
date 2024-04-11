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

        $class = Student::where('present_class', $staff->class_assigned)
        ->where('sch_id', $staff->sch_id)
        ->where('campus', $staff->campus)
        ->count();

        return [
            'status' => 'true',
            'message' => 'Class Population',
            'data' => $class
        ];
    }

    public function getallpopulation()
    {
        $staff = Auth::user();

        $all = Student::where('sch_id', $staff->sch_id)
        ->get();

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Student Population',
            'data' => $popul
        ];
    }

    public function getstaffpopulation()
    {
        $staff = Auth::user();

        $all = Staff::where('sch_id', $staff->sch_id)
        ->where('sch_id', $staff->sch_id)
        ->get();

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Staff Population',
            'data' => $popul
        ];
    }

    public function getteacherpopulation()
    {
        $staff = Auth::user();

        $all = Staff::where('designation_id', '4')
        ->where('sch_id', $staff->sch_id)
        ->get();

        $popul = $all->count();

        return [
            'status' => 'true',
            'message' => 'Total Teacher Population',
            'data' => $popul
        ];
    }

    public function getschoolpopulation()
    {
        $user = Auth::user();

        $staff = Staff::where('sch_id', $user->sch_id)
        ->get();

        $student = Student::where('sch_id', $user->sch_id)
        ->get();

        $total = $staff->count() + $student->count();

        return [
            'status' => 'true',
            'message' => 'Total School Population',
            'data' => $total
        ];
    }
}
