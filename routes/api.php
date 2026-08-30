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
use App\Http\Controllers\PlanController;
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
use App\Http\Controllers\StudentAttendanceController;
use App\Http\Controllers\StudentFeeHistoryController;
use App\Http\Controllers\AdmissionNumSearchController;
use App\Http\Controllers\GetPreschoolResultController;
use App\Http\Controllers\VehicleMaintenanceController;
use App\Http\Controllers\AssignmentPerformanceController;
use App\Http\Controllers\StudentAttendanceDateController;
use App\Http\Controllers\StudentBySessionTermClassController;

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});
Route::get("/optimize", function () {
    if (App::environment(["staging", "production"])) {
        Artisan::call("optimize:clear");
        return response()->json(
            ["message" => "Cache cleared successfully!"],
            200,
        );
    }
    return response()->json(["error" => "Unauthorized action."], 403);
});

Route::post("/test-email", [OtherController::class, "send"]);

Route::post("/seed/run", function () {
    $seederClass = Str::studly(request()->input("seeder_class"));

    if (!class_exists("Database\\Seeders\\{$seederClass}")) {
        return response()->json(
            [
                "error" => "Seeder class '{$seederClass}' not found in Database\\Seeders namespace.",
            ],
            404,
        );
    }

    try {
        Artisan::call("db:seed", [
            "--class" => $seederClass,
            "--force" => true,
        ]);

        return response()->json([
            "message" => "{$seederClass} executed successfully.",
            "output" => Artisan::output(),
        ]);
    } catch (\Exception $e) {
        return response()->json(
            [
                "error" => "Seeder failed to run.",
                "details" => $e->getMessage(),
            ],
            500,
        );
    }
});

Route::post("/run-migration", [OtherController::class, "migrate"]);

Route::middleware("check.allowed.url")->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);

    Route::post("/upload-campus-image", [
        CampusController::class,
        "uploadImage",
    ]);
    Route::post("/storage-link", [OtherController::class, "storageLink"]);
    Route::get("/school/detail/{school_id}", [
        SchoolsController::class,
        "schoolDetail",
    ])->where("school_id", ".+");

    Route::group(["middleware" => ["auth:sanctum"]], function () {
        Route::resource("/designation", DesignationController::class)->middleware("feature:designation_management");
        Route::resource("/staff", StaffController::class)->middleware("feature:staff_management");
        Route::resource("/campus", CampusController::class)->middleware("feature:campus_management");

        Route::controller(GeneralController::class)->group(function () {
            Route::patch("/enablecampus/{id}", "enableCampus")->middleware("feature:campus_management");
            Route::patch("/disablecampus/{id}", "disableCampus")->middleware("feature:campus_management");

            Route::patch("/enablestaff/{id}", "enableStaff")->middleware("feature:staff_management");
            Route::patch("/disablestaff/{id}", "disableStaff")->middleware("feature:staff_management");

            Route::patch("/enablestudent/{id}", "enableStudent")->middleware("feature:student_management");
            Route::patch("/disablestudent/{id}", "disableStudent")->middleware("feature:student_management");
        });

        Route::patch("/assignclass/{id}", [
            AssignClassController::class,
            "assign",
        ])->middleware("feature:class_management");
        Route::patch("/transferstudent/{id}", [
            TransferStudentController::class,
            "transfer",
        ])->middleware("feature:student_management");

        Route::post("/studentimport", [
            StudentImportController::class,
            "import",
        ])->middleware("feature:student_management");

        Route::controller(AcademicPeriodController::class)->group(function () {
            Route::post("/academicperiod", "changePeriod")->middleware([
                "check.subscription.status",
                "feature:academic_period_management",
            ]);
            Route::get("/getacademicperiod", "getPeriod")->middleware("feature:academic_period_management");
            Route::get("/getacademicsessions", "getSessions")->middleware("feature:academic_period_management");

            Route::post(
                "/current/academicperiod",
                "setCurrentAcademicPeriod",
            )->middleware([
                "check.subscription.status",
                "feature:academic_period_management",
            ]);
            Route::get("/current/academicperiod", "getCurrentAcademicPeriod")->middleware("feature:academic_period_management");
        });

        Route::get("/payment/{invoice_id}/get", [
            OtherController::class,
            "paymentinvoice",
        ])->middleware("feature:invoice_payment_management");

        Route::resource("/vehicle", VehicleController::class)->middleware("feature:vehicle_management");
        Route::resource("/vehiclelog", VehicleLogController::class)->middleware("feature:vehicle_management");
        Route::resource("/staffattendance", StaffAttendanceController::class)->middleware("feature:attendance_management");
        Route::resource("/codeconduct", CodeConductController::class)->middleware("feature:discipline_management");
        Route::resource("/class", ClassController::class)->middleware("feature:class_management");
        Route::resource("/disciplinary", DisciplinaryController::class)->middleware("feature:discipline_management");
        Route::resource("/student", StudentController::class)->middleware([
            "check.subscription.status",
            "feature:student_management",
        ]);
        Route::resource("/fee", FeeController::class)->middleware("feature:fee_management");
        Route::resource("/invoice", InvoiceController::class)->middleware([
            "check.subscription.status",
            "feature:invoice_payment_management",
        ]);
        Route::resource("/bank", BankController::class)->middleware("feature:account_management");
        Route::resource("/payment", PaymentController::class)->middleware("feature:invoice_payment_management");
        Route::resource("/chartaccount", ChartAccountController::class)->middleware("feature:account_management");
        Route::resource("/expenses", ExpensesController::class)->middleware("feature:account_management");
        Route::resource("/vendor", VendorController::class)->middleware("feature:account_management");
        Route::resource("/profile", ProfileController::class);
        Route::resource("/department", DepartmentController::class)->middleware("feature:department_management");
        Route::resource("/grading", GradingSystemController::class)->middleware("feature:grading_management");
        Route::resource("/gpa", GpaController::class)->middleware("feature:grading_management");
        Route::resource("/subjects", SubjectController::class)->middleware("feature:subject_management");
        Route::resource("/studentsubjects", RegisterSubjectController::class)->middleware("feature:subject_management");
        Route::resource("/dresscode", DressCodeController::class)->middleware("feature:dresscode_management");
        Route::resource(
            "/studentattendance",
            StudentAttendanceController::class,
        )->middleware("feature:attendance_management");
        Route::resource("/academiccalender", AcademicCalenderController::class)->middleware("feature:timetable_management");
        Route::resource("/timetable", TimetableController::class)->middleware("feature:timetable_management");
        Route::resource("/maximumscores", MaximumScoresController::class)->middleware("feature:grading_management");
        Route::resource(
            "/closingresumption",
            ClosingResumptionController::class,
        )->middleware("feature:timetable_management");
        Route::resource("/principalcomment", PrincipalCommentController::class)->middleware("feature:result_management");
        Route::resource("/skills", SkillsController::class)->middleware("feature:skills_management");
        Route::resource("/preschool", PreSchoolController::class)->middleware("feature:preschool_management");
        Route::resource("/reports", ReportsController::class);

        Route::middleware(["throttle:apis", "feature:result_management"])->group(function () {
            //New result form
            Route::middleware("check.subscription.status")
                ->controller(ResultController::class)
                ->group(function () {
                    Route::post("midTermResult", "midTerm");
                    Route::post("endTermResult", "endTerm");
                    Route::patch("release/result", "release");
                    Route::patch("withhold/result", "hold");
                    // New Get Result API
                    Route::get("/get-result", "getResult");
                });

            Route::prefix("staff")->group(function () {
                Route::get("/midtermresult/{student_id}/{term}/{session}", [
                    MidTermResultController::class,
                    "staffMidTerm",
                ])->where("session", ".+");
                Route::get("/endtermresult/{student_id}/{term}/{session}", [
                    EndTermResultController::class,
                    "staffEndTerm",
                ])->where("session", ".+");
            });

            Route::get(
                "/cumulativescore/{student_id}/{period}/{term}/{session}",
                [EndTermResultController::class, "cummulative"],
            )
                ->where("session", ".+")
                ->middleware("check.subscription.status");

            Route::get(
                "/end-term-class-average/{student_id}/{class_name}/{session}",
                [EndTermResultController::class, "endaverage"],
            )
                ->where("session", ".+")
                ->middleware("check.subscription.status");

            // Deprecating soon
            Route::get("/midtermresult/{student_id}/{term}/{session}", [
                MidTermResultController::class,
                "midterm",
            ])->where("session", ".+");
            Route::get("/endtermresult/{student_id}/{term}/{session}", [
                EndTermResultController::class,
                "endterm",
            ])->where("session", ".+");
            Route::get("/result/firstassesment/{student_id}/{term}/{session}", [
                MidTermResultController::class,
                "first",
            ])->where("session", ".+");
            Route::get(
                "/result/secondassesment/{student_id}/{term}/{session}",
                [MidTermResultController::class, "second"],
            )->where("session", ".+");

            Route::get(
                "/student-average/{student_id}/{class_name}/{term}/{session}",
                [EndTermResultController::class, "studentaverage"],
            )
                ->where("session", ".+")
                ->middleware("check.subscription.status");
        });

        //PreSchool Subject
        Route::controller(PreSchoolSubjectController::class)
            ->middleware("feature:preschool_management")
            ->group(function () {
                Route::post("/preschoolsubject", "addSubject");
                Route::get(
                    "/preschoolsubject/{period}/{term}/{session}",
                    "getSubject",
                )->where("session", ".+");
                Route::get("/preschoolsubject/{id}", "getSubjectID");
                Route::patch("/preschoolsubject/{id}", "editSubject");
                Route::delete("/preschoolsubject/{id}", "deleteSubject");
                Route::post("/preschoolsubjectclass", "addSubjectClass");
                Route::get(
                    "/preschoolsubjectclass/{period}/{term}/{session}",
                    "getSubjectClass",
                )->where("session", ".+");
                Route::get(
                    "/preschoolsubjects/{period}/{term}/{session}/{class}",
                    "getSubjectByClass",
                )->where("session", ".+");
            },
        );

        //Search Routes
        Route::get("/studentsessionsearch/{session}", [
            SessionSearchController::class,
            "sessionsearch",
        ])->where("session", ".+")->middleware("feature:student_management");
        Route::get("/admissionnumbersearch/{admissionnumber}", [
            AdmissionNumSearchController::class,
            "admissionsearch",
        ])->where("admissionnumber", ".+")->middleware("feature:student_management");

        Route::get("/incomereport/{term}/{session}", [
            IncomeReportController::class,
            "incomesearch",
        ])->where("session", ".+")->middleware("feature:financial_report_management");
        Route::get("/expensesreport/{term}/{session}", [
            ExpensesReportController::class,
            "expensesearch",
        ])->where("session", ".+")->middleware("feature:financial_report_management");

        Route::get("/graduatedstudent", [
            GraduatedStudentController::class,
            "graduate",
        ])->middleware("feature:student_management");
        Route::patch("/graduatestudent/{id}", [
            GraduatedStudentController::class,
            "graduatestudent",
        ])->middleware("feature:student_management");

        Route::get("/studentcreditors", [
            StudentCreditorsController::class,
            "creditors",
        ])->middleware("feature:financial_report_management");
        Route::get("/studentdebtors", [
            StudentDebtorController::class,
            "debtors",
        ])->middleware("feature:financial_report_management");

        Route::get("/creditors/{term}/{session}", [
            StudentCreditorsController::class,
            "creditorsByTermSession",
        ])->where("session", ".+")->middleware("feature:financial_report_management");
        Route::get("/debtors/{term}/{session}", [
            StudentDebtorController::class,
            "debtorsByTermSession",
        ])->where("session", ".+")->middleware("feature:financial_report_management");

        Route::patch("/withdrawstudent/{id}", [
            WithdrawStudentController::class,
            "withdraw",
        ])->middleware("feature:student_management");
        Route::patch("/acceptstudent/{id}", [
            WithdrawStudentController::class,
            "acceptStudent",
        ])->middleware("feature:student_management");
        Route::patch("/promotestudent/{id}", [
            PromoteStudentController::class,
            "promote",
        ])->middleware(["check.subscription.status", "feature:student_management"]);
        Route::patch("/promote-students", [
            PromoteStudentController::class,
            "promotestudents",
        ])->middleware(["check.subscription.status", "feature:student_management"]);

        Route::get("/expectedincome", [
            ExpectedIncomecontroller::class,
            "expected",
        ])->middleware("feature:financial_report_management");
        Route::get("/receivedincome", [
            ReceivedIncomeController::class,
            "received",
        ])->middleware("feature:financial_report_management");
        Route::get("/outstanding", [
            OutstandingController::class,
            "outstanding",
        ])->middleware("feature:financial_report_management");
        Route::get("/discount", [DiscountController::class, "discount"])->middleware("feature:fee_management");
        Route::get("/totalexpense", [
            TotalExpenseController::class,
            "totalexpense",
        ])->middleware("feature:account_management");
        Route::get("/accountbalance", [
            AccountBalanceController::class,
            "account",
        ])->middleware("feature:financial_report_management");
        Route::get("/studentfeehistory", [
            StudentFeeHistoryController::class,
            "feehistory",
        ])->middleware("feature:fee_management");
        Route::get("/studentinvoice", [
            StudentInvoiceController::class,
            "studentinvoices",
        ])->middleware("feature:invoice_payment_management");
        Route::get("/studentpreviousinvoice", [
            StudentInvoiceController::class,
            "studentprevinvoices",
        ])->middleware("feature:invoice_payment_management");
        Route::get("/school", [SchoolsController::class, "schools"]);
        Route::get("/school/plan", [PlanController::class, "plan"]);
        Route::get("/school/features", [PlanController::class, "features"]);
        Route::get("/student/{session}/{class}", [
            StudentBySessionTermClassController::class,
            "studentsessionclassterm",
        ])->where("session", ".+")->middleware("feature:student_management");

        Route::get("/studentlogindetails", [
            LoginDetailsController::class,
            "loginDetails",
        ])->middleware("feature:student_management");
        Route::get("/stafflogindetails", [
            LoginDetailsController::class,
            "staffloginDetails",
        ])->middleware("feature:staff_management"); // Deprecated

        // Student By Class (Principal)
        Route::get("/studentbyclass/{present_class}", [
            StudentBySessionTermClassController::class,
            "studentbyclass",
        ])->middleware("feature:student_management");
        Route::get("/attendance/{date}", [
            StudentAttendanceDateController::class,
            "attendancedate",
        ])->where("date", ".+")->middleware("feature:attendance_management");

        Route::controller(SubjectByClassController::class)
            ->middleware("feature:subject_management")
            ->group(function () {
                Route::get("/subject/{class}", "subjectByClass");
                Route::get("/subjectby/{id}", "subjectById");
                Route::get("/subject", "subjectByCampus");
                Route::get("/teacher-subject", "subjectByTeacher");
                Route::get("/student-subject", "subjectByStudent");
            });
    });

    Route::group(["middleware" => ["auth:sanctum"]], function () {
        Route::controller(ClassPopulationController::class)
            ->middleware("feature:student_management")
            ->group(function () {
                Route::get("/classpopulation", "getClassPopulation");
                Route::get("/studentpopulation", "getStudentPopulation");
                Route::get("/staffpopulation", "getStaffPopulation");
                Route::get("/schoolpopulation", "getSchoolPopulation");
                Route::get("/teacherpopulation", "getTeacherPopulation");
            });

        Route::controller(AssignedVehicleController::class)
            ->middleware("feature:vehicle_management")
            ->group(function () {
                Route::get("/assignedvehicle", "getVehicle");
                Route::get("/allassignedvehicle", "getVehicles");
            });

        Route::post("/busrouting", [BusRoutingController::class, "route"])->middleware("feature:vehicle_management");
        Route::patch("/releaseresult/{term}/{session}", [
            ReleaseResultsController::class,
            "release",
        ])->where("session", ".+")->middleware("feature:result_management");
        Route::post("/healthreport", [HealthReportController::class, "report"])->middleware("feature:health_management");
        Route::post("/vehiclemaintenance", [
            VehicleMaintenanceController::class,
            "maintenance",
        ])->middleware("feature:vehicle_management");
        Route::get("/vehiclemaintenance", [
            VehicleMaintenanceController::class,
            "getmaintenance",
        ])->middleware("feature:vehicle_management");
        Route::post("/setupdiscount", [
            DiscountController::class,
            "setupDiscount",
        ])->middleware("feature:fee_management");
        Route::post("/transferfund", [
            TransferFundsController::class,
            "transferFunds",
        ])->middleware("feature:account_management");
        Route::get("/getfunds", [TransferFundsController::class, "getFunds"])->middleware("feature:account_management");
        Route::get("/getsinglefund/{id}", [
            TransferFundsController::class,
            "getSingleFunds",
        ])->middleware("feature:account_management");
        Route::patch("/editfund/{id}", [
            TransferFundsController::class,
            "EditFunds",
        ])->middleware("feature:account_management");
        Route::delete("/deletefund/{id}", [
            TransferFundsController::class,
            "DeleteFunds",
        ])->middleware("feature:account_management");
        Route::get("/studentexcelimport", [
            SubjectByClassController::class,
            "studentExcelImport",
        ])->middleware("feature:student_management");
        Route::get("/invoicereport/{term}/{session}", [
            IncomeReportController::class,
            "invoicesearch",
        ])->where("session", ".+")->middleware("feature:invoice_payment_management");

        Route::get("/audits", [AuditLogController::class, "getAudit"])->middleware("feature:audit_management");
        // PreSchool Result
        Route::post("/preschoolresult", [
            PreSchoolResultController::class,
            "result",
        ])->middleware("feature:preschool_management");
        Route::get("/preschoolresult/{student_id}/{period}/{term}/{session}", [
            GetPreschoolResultController::class,
            "getResult",
        ])->where("session", ".+")->middleware("feature:preschool_management");

        Route::get("/computedresult/{period}/{term}/{session}", [
            GetPreschoolResultController::class,
            "getComputeResult",
        ])->where("session", ".+")->middleware("feature:preschool_management");

        // Assignment
        Route::controller(AssignmentController::class)
            ->middleware("feature:assignment_management")
            ->group(function () {
            Route::get("/assignment", "assign");
            Route::post("/objective-assignment", "objective");
            Route::post("/theory-assignment", "theory");
            Route::patch("/edit-obj-assignment", "editObjectiveAssign");
            Route::patch("/edit-thoery-assignment", "editTheoryAssign");
            Route::delete("/assignment/{id}", "delAssign");

            Route::post("/objective-assignment-answer", "objectiveAnswer");
            Route::post("/theory-assignment-answer", "theoryAnswer");
            Route::get(
                "/assignment-answer/{period}/{term}/{session}/{type}/{week}",
                "getAnswer",
            )->where("session", ".+");

            Route::post("/objective-assignment-mark", "objectiveMark");
            Route::patch(
                "/update/objective/assignment/mark",
                "updateObjectiveMark",
            );
            Route::post("/theory-assignment-mark", "theoryMark");
            Route::patch("/update/theory/assignment/mark", "updateTheoryMark");
            Route::get(
                "/marked-assignment/{period}/{term}/{session}/{type}/{week}",
                "marked",
            )->where("session", ".+");
            Route::get(
                "/marked-assignments/{student_id}/{period}/{term}/{session}/{type}/{week}",
                "markedByStudent",
            )->where("session", ".+");

            Route::post("/assignment-result", "result");
            Route::get(
                "/get-assignment-result/{period}/{term}/{session}/{type}/{week}",
                "resultAssign",
            )->where("session", ".+");
            Route::patch("/publish/assignment", "publish");
            Route::get(
                "/get-student-result/{student_id}/{period}/{term}/{session}/{type}",
                "getStudentResult",
            )->where("session", ".+");
        });

        Route::get("/assignment/performance", [
            AssignmentPerformanceController::class,
            "chart",
        ])->middleware("feature:assignment_management");

        // Assign Subjects to class
        Route::post("/subjects-to-class", [
            AssignSubjectsController::class,
            "assign",
        ])->middleware("feature:subject_management");
        Route::post("/add-dos", [SchoolsController::class, "dos"]);
        Route::get("/dos", [SchoolsController::class, "getdos"]);

        Route::post("/extra-curricular", [OtherController::class, "extra"])->middleware("feature:communication_management");
        Route::get("/extra-curricular", [OtherController::class, "getextra"])->middleware("feature:communication_management");
        Route::delete("/delete-extra-curricular/{id}", [
            OtherController::class,
            "delextra",
        ])->middleware("feature:communication_management");

        Route::post("/preschoolcurricular", [
            OtherController::class,
            "preextra",
        ])->middleware("feature:preschool_management");
        Route::get("/preschoolcurricular", [
            OtherController::class,
            "pregetextra",
        ])->middleware("feature:preschool_management");
        Route::delete("/delete-preschoolcurricular/{id}", [
            OtherController::class,
            "predelextra",
        ])->middleware("feature:preschool_management");
        Route::get("/role", [OtherController::class, "role"]);

        Route::get("/broadsheet/{class_name}/{term}/{session}", [
            BroadSheetController::class,
            "broadsheet",
        ])->where("session", ".+")->middleware("feature:result_management");

        // Admission Number Settings
        Route::post("admission-number/settings", [
            OtherController::class,
            "admissionNumberSettings",
        ])->middleware("feature:student_management");
        Route::get("admission-number/settings/{sch_id}", [
            OtherController::class,
            "getAdmissionNumberSettings",
        ])->where("sch_id", ".+")->middleware("feature:student_management");

        // Staff by class
        Route::get("/staffbyclass/{class}", [
            OtherController::class,
            "staffByClass",
        ])->middleware("feature:staff_management");

        // Staff & Student Attendance
        Route::prefix("scan/attendance")
            ->controller(ScanAttendanceController::class)
            ->middleware("feature:attendance_management")
            ->group(function () {
                Route::post("/staff", "staffAttendance");
            });

        // Announcments
        Route::get("/announcements", [
            GeneralController::class,
            "getAnnouncements",
        ])->middleware("feature:communication_management");

        Route::post("/changepassword", [AuthController::class, "change"]);
        Route::post("/logout", [AuthController::class, "logout"]);
    });
});
