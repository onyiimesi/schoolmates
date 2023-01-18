<?php

use App\Http\Controllers\AcceptStudentController;
use App\Http\Controllers\AccountBalanceController;
use App\Http\Controllers\AdmissionNumSearchController;
use App\Http\Controllers\AssignClassController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ChartAccountController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\CodeConductController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DisableCampusController;
use App\Http\Controllers\DisableStaffController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\EnableCampusController;
use App\Http\Controllers\EnableStaffController;
use App\Http\Controllers\ExpectedIncomecontroller;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExpensesReportController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\GraduatedStudentController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\OutstandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReceivedIncomeController;
use App\Http\Controllers\RegisterSubjectController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SessionSearchController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCreditorsController;
use App\Http\Controllers\StudentDebtorController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TotalExpenseController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleLogController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\WithdrawStudentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);


Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::resource('/designation', DesignationController::class);
    Route::resource('/staff', StaffController::class);
    Route::resource('/campus', CampusController::class);


    Route::patch('/enablecampus/{id}', [EnableCampusController::class, 'enable']);
    Route::patch('/disablecampus/{id}', [DisableCampusController::class, 'disable']);
    
    Route::patch('/enablestaff/{id}', [EnableStaffController::class, 'enable']);
    Route::patch('/disablestaff/{id}', [DisableStaffController::class, 'disable']);

    Route::patch('/assignclass/{id}', [AssignClassController::class, 'assign']);

    Route::post('/studentimport', [StudentImportController::class, 'import']);


    Route::resource('/vehicle', VehicleController::class);
    Route::resource('/vehiclelog', VehicleLogController::class);
    Route::resource('/staffattendance', StaffAttendanceController::class);
    Route::resource('/codeconduct', CodeConductController::class);
    Route::resource('/class', ClassController::class);
    Route::resource('/disciplinary', DisciplinaryController::class);
    Route::resource('/student', StudentController::class);
    Route::resource('/fee', FeeController::class);
    Route::resource('/invoice', InvoiceController::class);
    Route::resource('/bank', BankController::class);
    Route::resource('/payment', PaymentController::class);
    Route::resource('/chartaccount', ChartAccountController::class);
    Route::resource('/expenses', ExpensesController::class);
    Route::resource('/vendor', VendorController::class);
    Route::resource('/profile', ProfileController::class);
    Route::resource('/department', DepartmentController::class);
    Route::resource('/grading', GradingSystemController::class);
    Route::resource('/result', ResultController::class);
    Route::resource('/subjects', SubjectController::class);
    Route::resource('/studentsubjects', RegisterSubjectController::class);

    //Search Routes
    Route::get("/studentsessionsearch/{session}", [SessionSearchController::class, 'sessionsearch'])
    ->where('session', '.+');
    Route::get("/admissionnumbersearch/{admissionnumber}", [AdmissionNumSearchController::class, 'admissionsearch'])
    ->where('admissionnumber', '.+');

    Route::get("/incomereport/{term}/{session}", [IncomeReportController::class, 'incomesearch'])
    ->where('session', '.+');
    Route::get("/expensesreport/{term}/{session}", [ExpensesReportController::class, 'expensesearch'])
    ->where('session', '.+');

    Route::get("/graduatedstudent", [GraduatedStudentController::class, 'graduate']);


    // Route::post("/studentsessionsearch", [SessionSearchController::class, 'sessionsearch']);

    Route::get("/studentcreditors", [StudentCreditorsController::class, 'creditors']);
    Route::get("/studentdebtors", [StudentDebtorController::class, 'debtors']);
    

    Route::patch('/withdrawstudent/{id}', [WithdrawStudentController::class, 'withdraw']);
    Route::patch('/acceptstudent/{id}', [AcceptStudentController::class, 'accept']);

    Route::get("/expectedincome", [ExpectedIncomecontroller::class, 'expected']);
    Route::get("/receivedincome", [ReceivedIncomeController::class, 'received']);
    Route::get("/outstanding", [OutstandingController::class, 'outstanding']);
    Route::get("/discount", [DiscountController::class, 'discount']);
    Route::get("/totalexpense", [TotalExpenseController::class, 'totalexpense']);
    Route::get("/accountbalance", [AccountBalanceController::class, 'account']);





    Route::post('/changepassword', [AuthController::class, 'change']);
    Route::post('/logout', [AuthController::class, 'logout']);
});