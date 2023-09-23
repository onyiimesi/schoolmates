<?php

use App\Http\Controllers\AcademicCalenderController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AcceptStudentController;
use App\Http\Controllers\AccountBalanceController;
use App\Http\Controllers\AdmissionNumSearchController;
use App\Http\Controllers\AssignClassController;
use App\Http\Controllers\AssignedVehicleController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\BusRoutingController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ChartAccountController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\ClassPopulationController;
use App\Http\Controllers\ClosingResumptionController;
use App\Http\Controllers\CodeConductController;
use App\Http\Controllers\CommunicationBookController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DisableCampusController;
use App\Http\Controllers\DisableStaffController;
use App\Http\Controllers\DisableStudentController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\DressCodeController;
use App\Http\Controllers\EnableCampusController;
use App\Http\Controllers\EnableStaffController;
use App\Http\Controllers\EnableStudentController;
use App\Http\Controllers\EndTermResultController;
use App\Http\Controllers\ExpectedIncomecontroller;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\ExpensesReportController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GetPreschoolResultController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\GraduatedStudentController;
use App\Http\Controllers\HealthReportController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LoginDetailsController;
use App\Http\Controllers\MaximumScoresController;
use App\Http\Controllers\MidTermResultController;
use App\Http\Controllers\OutstandingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PreSchoolController;
use App\Http\Controllers\PreSchoolResultController;
use App\Http\Controllers\PreSchoolSubjectController;
use App\Http\Controllers\PrincipalCommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromoteStudentController;
use App\Http\Controllers\ReceivedIncomeController;
use App\Http\Controllers\RegisterSubjectController;
use App\Http\Controllers\ReleaseResultsController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\SessionSearchController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentAttendanceDateController;
use App\Http\Controllers\StudentBySessionTermClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentCreditorsController;
use App\Http\Controllers\StudentDebtorController;
use App\Http\Controllers\StudentFeeHistoryController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\StudentInvoiceController;
use App\Http\Controllers\SubjectByClassController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\TotalExpenseController;
use App\Http\Controllers\TransferFundsController;
use App\Http\Controllers\TransferStudentController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleLogController;
use App\Http\Controllers\VehicleMaintenanceController;
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

    Route::patch('/enablestudent/{id}', [EnableStudentController::class, 'enable']);
    Route::patch('/disablestudent/{id}', [DisableStudentController::class, 'disable']);

    Route::patch('/assignclass/{id}', [AssignClassController::class, 'assign']);
    Route::patch('/transferstudent/{id}', [TransferStudentController::class, 'transfer']);

    Route::post('/studentimport', [StudentImportController::class, 'import']);
    Route::post('/academicperiod', [AcademicPeriodController::class, 'changeperiod']);
    Route::get('/getacademicperiod', [AcademicPeriodController::class, 'getperiod']);
    Route::get('/getacademicsessions', [AcademicPeriodController::class, 'getsessions']);

    Route::post('/communicationbook', [CommunicationBookController::class, 'communicate']);
    Route::get('/communicationbook', [CommunicationBookController::class, 'getmessage']);


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
    Route::resource('/dresscode', DressCodeController::class);
    Route::resource('/studentattendance', StudentAttendanceController::class);
    Route::resource('/academiccalender', AcademicCalenderController::class);
    Route::resource('/timetable', TimetableController::class);
    Route::resource('/maximumscores', MaximumScoresController::class);
    Route::resource('/closingresumption', ClosingResumptionController::class);
    Route::resource('/principalcomment', PrincipalCommentController::class);
    Route::resource('/skills', SkillsController::class);
    Route::resource('/preschool', PreSchoolController::class);



    //PreSchool Subject
    Route::post('/preschoolsubject', [PreSchoolSubjectController::class, 'addSubject']);
    Route::get('/preschoolsubject/{period}/{term}/{session}', [PreSchoolSubjectController::class, 'getSubject'])->where('session', '.+');
    Route::get('/preschoolsubject/{id}', [PreSchoolSubjectController::class, 'getSubjectID']);
    Route::patch('/preschoolsubject/{id}', [PreSchoolSubjectController::class, 'editSubject']);
    Route::delete('/preschoolsubject/{id}', [PreSchoolSubjectController::class, 'deleteSubject']);

    Route::post('/preschoolsubjectclass', [PreSchoolSubjectController::class, 'addSubjectClass']);
    Route::get('/preschoolsubjectclass/{period}/{term}/{session}', [PreSchoolSubjectController::class, 'getSubjectClass'])->where('session', '.+');

    Route::get('/preschoolsubjects/{period}/{term}/{session}/{class}', [PreSchoolSubjectController::class, 'getSubjectByClass'])->where('session', '.+');


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
    Route::patch("/graduatestudent/{id}", [GraduatedStudentController::class, 'graduatestudent']);


    // Route::post("/studentsessionsearch", [SessionSearchController::class, 'sessionsearch']);

    Route::get("/studentcreditors", [StudentCreditorsController::class, 'creditors']);
    Route::get("/studentdebtors", [StudentDebtorController::class, 'debtors']);

    Route::get("/creditors/{term}/{session}", [StudentCreditorsController::class, 'creditorsByTermSession'])
    ->where('session', '.+');
    Route::get("/debtors/{term}/{session}", [StudentDebtorController::class, 'debtorsByTermSession'])
    ->where('session', '.+');


    Route::patch('/withdrawstudent/{id}', [WithdrawStudentController::class, 'withdraw']);
    Route::patch('/acceptstudent/{id}', [AcceptStudentController::class, 'accept']);
    Route::patch('/promotestudent/{id}', [PromoteStudentController::class, 'promote']);

    Route::get("/expectedincome", [ExpectedIncomecontroller::class, 'expected']);
    Route::get("/receivedincome", [ReceivedIncomeController::class, 'received']);
    Route::get("/outstanding", [OutstandingController::class, 'outstanding']);
    Route::get("/discount", [DiscountController::class, 'discount']);
    Route::get("/totalexpense", [TotalExpenseController::class, 'totalexpense']);
    Route::get("/accountbalance", [AccountBalanceController::class, 'account']);
    Route::get("/studentfeehistory", [StudentFeeHistoryController::class, 'feehistory']);
    Route::get("/studentinvoice", [StudentInvoiceController::class, 'studentinvoices']);
    Route::get("/studentpreviousinvoice", [StudentInvoiceController::class, 'studentprevinvoices']);
    Route::get("/school", [SchoolsController::class, 'schools']);
    Route::get("/subject/{class}", [SubjectByClassController::class, 'subjectbyclass']);
    Route::get("/teacher-subject", [SubjectByClassController::class, 'subjectbyteacher']);
    Route::get("/student-subject", [SubjectByClassController::class, 'subjectbystudent']);
    Route::get("/student/{session}/{class}", [StudentBySessionTermClassController::class, 'studentsessionclassterm'])
    ->where('session', '.+');

    Route::get("/studentlogindetails", [LoginDetailsController::class, 'loginDetails']);
    Route::get("/stafflogindetails", [LoginDetailsController::class, 'staffloginDetails']);

    // Student By Class (Principal)
    Route::get("/studentbyclass/{present_class}", [StudentBySessionTermClassController::class, 'studentbyclass']);

    Route::get("/attendance/{date}", [StudentAttendanceDateController::class, 'attendancedate'])
    ->where('date', '.+');
    Route::get("/midtermresult/{student_id}/{term}/{session}", [MidTermResultController::class, 'midterm'])
    ->where('session', '.+');
    Route::get("/endtermresult/{student_id}/{term}/{session}", [EndTermResultController::class, 'endterm'])
    ->where('session', '.+');
    Route::get('/classpopulation', [ClassPopulationController::class, 'getclasspopulation']);
    Route::get('/studentpopulation', [ClassPopulationController::class, 'getallpopulation']);
    Route::get('/staffpopulation', [ClassPopulationController::class, 'getstaffpopulation']);
    Route::get('/schoolpopulation', [ClassPopulationController::class, 'getschoolpopulation']);
    Route::get('/teacherpopulation', [ClassPopulationController::class, 'getteacherpopulation']);
    Route::get('/assignedvehicle', [AssignedVehicleController::class, 'getvehicle']);
    Route::get('/allassignedvehicle', [AssignedVehicleController::class, 'getvehicles']);

    Route::post('/busrouting', [BusRoutingController::class, 'route']);
    Route::patch("/releaseresult/{term}/{session}", [ReleaseResultsController::class, 'release'])
    ->where('session', '.+');
    Route::post('/healthreport', [HealthReportController::class, 'report']);
    Route::post('/vehiclemaintenance', [VehicleMaintenanceController::class, 'maintenance']);
    Route::get('/vehiclemaintenance', [VehicleMaintenanceController::class, 'getmaintenance']);
    Route::post("/setupdiscount", [DiscountController::class, 'setupDiscount']);
    Route::post("/transferfund", [TransferFundsController::class, 'transferFunds']);
    Route::get("/getfunds", [TransferFundsController::class, 'getFunds']);
    Route::get("/getsinglefund/{id}", [TransferFundsController::class, 'getSingleFunds']);
    Route::patch("/editfund/{id}", [TransferFundsController::class, 'EditFunds']);
    Route::delete("/deletefund/{id}", [TransferFundsController::class, 'DeleteFunds']);
    Route::get("/studentexcelimport", [SubjectByClassController::class, 'studentExcelImport']);
    Route::get("/invoicereport/{term}/{session}", [IncomeReportController::class, 'invoicesearch'])
    ->where('session', '.+');

    Route::get("/audits", [AuditLogController::class, 'getAudit']);

    // PreSchool Result
    Route::post('/preschoolresult', [PreSchoolResultController::class, 'result']);
    Route::get('/preschoolresult/{student_id}/{period}/{term}/{session}', [GetPreschoolResultController::class, 'getResult'])->where('session', '.+');

    Route::get('/computedresult/{period}/{term}/{session}', [GetPreschoolResultController::class, 'getComputeResult'])->where('session', '.+');

    Route::post('/objective-assignment', [AssignmentController::class, 'objective']);
    Route::post('/theory-assignment', [AssignmentController::class, 'theory']);
    Route::get('/assignment/{period}/{term}/{session}/{type}', [AssignmentController::class, 'assign'])
    ->where('session', '.+');

    Route::post('/objective-assignment-answer', [AssignmentController::class, 'objectiveanswer']);
    Route::post('/theory-assignment-answer', [AssignmentController::class, 'theoryanswer']);

    Route::get('/assignment-answer/{period}/{term}/{session}/{type}', [AssignmentController::class, 'getanswer'])
    ->where('session', '.+');


    Route::post('/changepassword', [AuthController::class, 'change']);
    Route::post('/logout', [AuthController::class, 'logout']);
});
