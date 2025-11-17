<?php

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\GpaController;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\CampusController;
use App\Http\Controllers\ResultController;
use App\Http\Controllers\SkillsController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SchoolsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\DressCodeController;
use App\Http\Controllers\PreSchoolController;
use App\Http\Controllers\ResultTwoController;
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\BroadSheetController;
use App\Http\Controllers\BusRoutingController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\VehicleLogController;
use App\Http\Controllers\AssignClassController;
use App\Http\Controllers\CodeConductController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\OutstandingController;
use App\Http\Controllers\ChartAccountController;
use App\Http\Controllers\DisciplinaryController;
use App\Http\Controllers\HealthReportController;
use App\Http\Controllers\IncomeReportController;
use App\Http\Controllers\LoginDetailsController;
use App\Http\Controllers\TotalExpenseController;
use App\Http\Controllers\AcceptStudentController;
use App\Http\Controllers\EndTermResultController;
use App\Http\Controllers\GradingSystemController;
use App\Http\Controllers\MaximumScoresController;
use App\Http\Controllers\MidTermResultController;
use App\Http\Controllers\SessionSearchController;
use App\Http\Controllers\StudentDebtorController;
use App\Http\Controllers\StudentImportController;
use App\Http\Controllers\TransferFundsController;
use App\Http\Controllers\AcademicPeriodController;
use App\Http\Controllers\AccountBalanceController;
use App\Http\Controllers\AssignSubjectsController;
use App\Http\Controllers\ExpectedIncomecontroller;
use App\Http\Controllers\ExpensesReportController;
use App\Http\Controllers\PromoteStudentController;
use App\Http\Controllers\ReceivedIncomeController;
use App\Http\Controllers\ReleaseResultsController;
use App\Http\Controllers\ScanAttendanceController;
use App\Http\Controllers\StudentInvoiceController;
use App\Http\Controllers\SubjectByClassController;
use App\Http\Controllers\AssignedVehicleController;
use App\Http\Controllers\ClassPopulationController;
use App\Http\Controllers\PreSchoolResultController;
use App\Http\Controllers\RegisterSubjectController;
use App\Http\Controllers\StaffAttendanceController;
use App\Http\Controllers\TransferStudentController;
use App\Http\Controllers\WithdrawStudentController;
use App\Http\Controllers\AcademicCalenderController;
use App\Http\Controllers\GraduatedStudentController;
use App\Http\Controllers\PreSchoolSubjectController;
use App\Http\Controllers\PrincipalCommentController;
use App\Http\Controllers\StudentCreditorsController;
use App\Http\Controllers\ClosingResumptionController;
use App\Http\Controllers\CommunicationBookController;
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentFeeHistoryController;
use App\Http\Controllers\AdmissionNumSearchController;
use App\Http\Controllers\GetPreschoolResultController;
use App\Http\Controllers\VehicleMaintenanceController;
use App\Http\Controllers\AssignmentPerformanceController;
use App\Http\Controllers\StudentAttendanceDateController;
use App\Http\Controllers\StudentBySessionTermClassController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/optimize', function () {
    if (App::environment(['staging', 'production'])) {
        Artisan::call('optimize:clear');
        return response()->json(['message' => 'Cache cleared successfully!'], 200);
    }
    return response()->json(['error' => 'Unauthorized action.'], 403);
});

Route::post('/test-email', [OtherController::class, 'send']);

Route::post('/seed/run', function () {
    $seederClass = Str::studly(request()->input('seeder_class'));

    if (!class_exists("Database\\Seeders\\{$seederClass}")) {
        return response()->json([
            'error' => "Seeder class '{$seederClass}' not found in Database\\Seeders namespace."
        ], 404);
    }

    try {
        Artisan::call('db:seed', [
            '--class' => $seederClass,
            '--force' => true,
        ]);

        return response()->json([
            'message' => "{$seederClass} executed successfully.",
            'output' => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Seeder failed to run.',
            'details' => $e->getMessage(),
        ], 500);
    }
});

Route::post('/run-migration', [OtherController::class, 'migrate']);

Route::middleware('check.allowed.url')
    ->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/register', [AuthController::class, 'register']);

        Route::post('/upload-campus-image', [CampusController::class, 'uploadImage']);
        Route::post('/storage-link', [OtherController::class, 'storageLink']);

        Route::group(['middleware' => ['auth:sanctum']], function(){
            Route::resource('/designation', DesignationController::class);
            Route::resource('/staff', StaffController::class);
            Route::resource('/campus', CampusController::class);

            Route::controller(GeneralController::class)
                ->group(function () {
                    Route::patch('/enablecampus/{id}', 'enableCampus');
                    Route::patch('/disablecampus/{id}', 'disableCampus');

                    Route::patch('/enablestaff/{id}', 'enableStaff');
                    Route::patch('/disablestaff/{id}', 'disableStaff');

                    Route::patch('/enablestudent/{id}', 'enableStudent');
                    Route::patch('/disablestudent/{id}', 'disableStudent');
                });

            Route::patch('/assignclass/{id}', [AssignClassController::class, 'assign']);
            Route::patch('/transferstudent/{id}', [TransferStudentController::class, 'transfer']);

            Route::post('/studentimport', [StudentImportController::class, 'import']);

            Route::controller(AcademicPeriodController::class)->group(function () {
                Route::post('/academicperiod', 'changeperiod');
                Route::get('/getacademicperiod', 'getperiod');
                Route::get('/getacademicsessions', 'getsessions');

                Route::post('/current/academicperiod', 'setCurrentAcademicPeriod');
                Route::get('/current/academicperiod', 'getCurrentAcademicPeriod');
            });

            // Route::post('/communicationbook', [CommunicationBookController::class, 'communicate']);
            // Route::get('/communicationbook', [CommunicationBookController::class, 'getmessage']);

            Route::get('/payment/{invoice_id}/get', [OtherController::class, 'paymentinvoice']);

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
            Route::resource('/gpa', GpaController::class);
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
            Route::resource('/reports', ReportsController::class);

            Route::middleware(['throttle:apis'])->group(function () {
                //New result form
                Route::controller(ResultTwoController::class)
                    ->group(function () {
                        Route::post('midTermResult', 'midTerm');
                        Route::post('endTermResult', 'endTerm');
                        Route::patch('release/result', 'release');
                        Route::patch('withhold/result', 'hold');
                    });

                // New Result API
                Route::get("/get-result", [EndTermResultController::class, 'getResult']);

                Route::prefix('staff')
                    ->group(function () {
                        Route::get("/midtermresult/{student_id}/{term}/{session}", [MidTermResultController::class, 'staffMidTerm'])
                            ->where('session', '.+');
                        Route::get("/endtermresult/{student_id}/{term}/{session}", [EndTermResultController::class, 'staffEndTerm'])
                            ->where('session', '.+');
                    });

                Route::get("/cumulativescore/{student_id}/{period}/{term}/{session}", [EndTermResultController::class, 'cummulative'])
                    ->where('session', '.+');
                Route::get("/end-term-class-average/{student_id}/{class_name}/{session}", [EndTermResultController::class, 'endaverage'])
                    ->where('session', '.+');

                // Deprecating soon
                Route::get("/midtermresult/{student_id}/{term}/{session}", [MidTermResultController::class, 'midterm'])
                    ->where('session', '.+');
                Route::get("/endtermresult/{student_id}/{term}/{session}", [EndTermResultController::class, 'endterm'])
                    ->where('session', '.+');
                Route::get("/result/firstassesment/{student_id}/{term}/{session}", [MidTermResultController::class, 'first'])
                    ->where('session', '.+');
                Route::get("/result/secondassesment/{student_id}/{term}/{session}", [MidTermResultController::class, 'second'])
                    ->where('session', '.+');

                Route::get("/student-average/{student_id}/{class_name}/{term}/{session}", [EndTermResultController::class, 'studentaverage'])
                    ->where('session', '.+');
            });

            //PreSchool Subject
            Route::controller(PreSchoolSubjectController::class)
                ->group(function () {
                    Route::post('/preschoolsubject', 'addSubject');
                    Route::get('/preschoolsubject/{period}/{term}/{session}', 'getSubject')->where('session', '.+');
                    Route::get('/preschoolsubject/{id}', 'getSubjectID');
                    Route::patch('/preschoolsubject/{id}', 'editSubject');
                    Route::delete('/preschoolsubject/{id}', 'deleteSubject');
                    Route::post('/preschoolsubjectclass', 'addSubjectClass');
                    Route::get('/preschoolsubjectclass/{period}/{term}/{session}', 'getSubjectClass')->where('session', '.+');
                    Route::get('/preschoolsubjects/{period}/{term}/{session}/{class}', 'getSubjectByClass')->where('session', '.+');
                });

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

            Route::get("/studentcreditors", [StudentCreditorsController::class, 'creditors']);
            Route::get("/studentdebtors", [StudentDebtorController::class, 'debtors']);

            Route::get("/creditors/{term}/{session}", [StudentCreditorsController::class, 'creditorsByTermSession'])
            ->where('session', '.+');
            Route::get("/debtors/{term}/{session}", [StudentDebtorController::class, 'debtorsByTermSession'])
            ->where('session', '.+');

            Route::patch('/withdrawstudent/{id}', [WithdrawStudentController::class, 'withdraw']);
            Route::patch('/acceptstudent/{id}', [AcceptStudentController::class, 'accept']);
            Route::patch('/promotestudent/{id}', [PromoteStudentController::class, 'promote']);
            Route::patch('/promote-students', [PromoteStudentController::class, 'promotestudents']);

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
            Route::get("/student/{session}/{class}", [StudentBySessionTermClassController::class, 'studentsessionclassterm'])
            ->where('session', '.+');

            Route::get("/studentlogindetails", [LoginDetailsController::class, 'loginDetails']);
            Route::get("/stafflogindetails", [LoginDetailsController::class, 'staffloginDetails']); // Deprecated

            // Student By Class (Principal)
            Route::get("/studentbyclass/{present_class}", [StudentBySessionTermClassController::class, 'studentbyclass']);
            Route::get("/attendance/{date}", [StudentAttendanceDateController::class, 'attendancedate'])
            ->where('date', '.+');

            Route::controller(SubjectByClassController::class)
                ->group(function () {
                    Route::get("/subject/{class}", 'subjectByClass');
                    Route::get("/subjectby/{id}", 'subjectById');
                    Route::get("/subject", 'subjectByCampus');
                    Route::get("/teacher-subject", 'subjectByTeacher');
                    Route::get("/student-subject", 'subjectByStudent');
                });
        });

        Route::group(['middleware' => ['auth:sanctum']], function(){
            Route::controller(ClassPopulationController::class)
                ->group(function () {
                    Route::get('/classpopulation', 'getClassPopulation');
                    Route::get('/studentpopulation', 'getStudentPopulation');
                    Route::get('/staffpopulation', 'getStaffPopulation');
                    Route::get('/schoolpopulation', 'getSchoolPopulation');
                    Route::get('/teacherpopulation', 'getTeacherPopulation');
                });

            Route::controller(AssignedVehicleController::class)
                ->group(function () {
                    Route::get('/assignedvehicle', 'getVehicle');
                    Route::get('/allassignedvehicle', 'getVehicles');
                });

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
            Route::get('/preschoolresult/{student_id}/{period}/{term}/{session}', [GetPreschoolResultController::class, 'getResult'])
                ->where('session', '.+');

            Route::get('/computedresult/{period}/{term}/{session}', [GetPreschoolResultController::class, 'getComputeResult'])
                ->where('session', '.+');

            // Assignment
            Route::controller(AssignmentController::class)
                ->group(function () {
                    Route::get('/assignment', 'assign');
                    Route::post('/objective-assignment', 'objective');
                    Route::post('/theory-assignment', 'theory');
                    Route::patch("/edit-obj-assignment", 'editObjectiveAssign');
                    Route::patch("/edit-thoery-assignment", 'editTheoryAssign');
                    Route::delete("/assignment/{id}", 'delAssign');

                    Route::post('/objective-assignment-answer', 'objectiveAnswer');
                    Route::post('/theory-assignment-answer', 'theoryAnswer');
                    Route::get('/assignment-answer/{period}/{term}/{session}/{type}/{week}', 'getAnswer')
                        ->where('session', '.+');

                    Route::post('/objective-assignment-mark', 'objectiveMark');
                    Route::patch('/update/objective/assignment/mark', 'updateObjectiveMark');
                    Route::post('/theory-assignment-mark', 'theoryMark');
                    Route::patch('/update/theory/assignment/mark', 'updateTheoryMark');
                    Route::get('/marked-assignment/{period}/{term}/{session}/{type}/{week}', 'marked')
                    ->where('session', '.+');
                    Route::get('/marked-assignments/{student_id}/{period}/{term}/{session}/{type}/{week}', 'markedByStudent')
                    ->where('session', '.+');

                    Route::post('/assignment-result', 'result');
                    Route::get('/get-assignment-result/{period}/{term}/{session}/{type}/{week}', 'resultAssign')
                    ->where('session', '.+');
                    Route::patch("/publish/assignment", 'publish');
                    Route::get('/get-student-result/{student_id}/{period}/{term}/{session}/{type}', 'getStudentResult')
                    ->where('session', '.+');
                });

            Route::get('/assignment/performance', [AssignmentPerformanceController::class, 'chart']);

            // Assign Subjects to class
            Route::post('/subjects-to-class', [AssignSubjectsController::class, 'assign']);
            Route::post("/add-dos", [SchoolsController::class, 'dos']);
            Route::get("/dos", [SchoolsController::class, 'getdos']);

            Route::post("/extra-curricular", [OtherController::class, 'extra']);
            Route::get("/extra-curricular", [OtherController::class, 'getextra']);
            Route::delete("/delete-extra-curricular/{id}", [OtherController::class, 'delextra']);

            Route::post("/preschoolcurricular", [OtherController::class, 'preextra']);
            Route::get("/preschoolcurricular", [OtherController::class, 'pregetextra']);
            Route::delete("/delete-preschoolcurricular/{id}", [OtherController::class, 'predelextra']);
            Route::get("/role", [OtherController::class, 'role']);
            Route::get("/broadsheet/{class_name}/{term}/{session}", [BroadSheetController::class, 'broadsheet'])
                ->where('session', '.+');

            // Admission Number Settings
            Route::post('admission-number/settings', [OtherController::class, 'admissionNumberSettings']);
            Route::get('admission-number/settings/{sch_id}', [OtherController::class, 'getAdmissionNumberSettings'])
                ->where('sch_id', '.+');

            // Staff by class
            Route::get("/staffbyclass/{class}", [OtherController::class, 'staffByClass']);

            // Staff & Student Attendance
            Route::prefix('scan/attendance')
                ->controller(ScanAttendanceController::class)
                ->group(function () {
                    Route::post("/staff", 'staffAttendance');
                });

            // Announcments
            Route::get('/announcements', [GeneralController::class, 'getAnnouncements']);

            Route::post('/changepassword', [AuthController::class, 'change']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

