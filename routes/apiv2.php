<?php

use App\Http\Controllers\v2\CbtController;
use App\Http\Controllers\v2\CommunicationBookController;
use App\Http\Controllers\v2\FlipClassController;
use App\Http\Controllers\v2\LessonNoteController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Route::middleware(['throttle:apis'])->group(function () {
// });
    Route::group(['middleware' => ['auth:sanctum']], function(){

        // CBT
        Route::prefix('cbt')->group(function () {
            Route::post('/setup', [CbtController::class, 'addSetup']);
            Route::get('/setup/{period}/{term}/{session}/{subject_id}/{question_type}', [CbtController::class, 'getSetup'])
            ->where('session', '.+');

            Route::post('/add/question', [CbtController::class, 'addQuestion']);
            Route::get('/questions/{period}/{term}/{session}/{subject_id}/{question_type}/get', [CbtController::class, 'getQuestions'])
            ->where('session', '.+');
            Route::patch('/update/question/{id}', [CbtController::class, 'editQuestion']);
            Route::delete('/delete/question/{id}', [CbtController::class, 'deleteQuestion']);
            Route::get('/{period}/{term}/{session}/{question_type}/{subject_id}/{student_id}/student', [CbtController::class, 'getStudentAnswer'])
            ->where('session', '.+');
            Route::patch("/publish", [CbtController::class, 'publish']);
            Route::get("/performance", [CbtController::class, 'performance']);

            Route::prefix('answer')->group(function () {
                Route::post('/add', [CbtController::class, 'addAnswer']);
                Route::get('/{period}/{term}/{session}/{question_type}/{subject_id}', [CbtController::class, 'getAnswerSubject'])
                ->where('session', '.+');
            });

            Route::prefix('result')->group(function () {
                Route::post('/add', [CbtController::class, 'addResult']);
                Route::get('/{student_id}/{period}/{term}/{session}/{question_type}/{subject_id}', [CbtController::class, 'getResultStudent'])
                ->where('session', '.+');
            });
        });

        // Lesson note
        Route::prefix('lessonnote')->group(function () {
            Route::post('/add', [LessonNoteController::class, 'addLesson']);
            Route::get('/single/{lesson_id}/{class_id}/{subject_id}/{week}/{term}/{session}', [LessonNoteController::class, 'getOneLesson'])
            ->where('session', '.+');
            Route::get('/{class_id}/{subject_id}/{week}/{term}/{session}', [LessonNoteController::class, 'getLesson'])
            ->where('session', '.+');
            Route::patch('/edit/{lesson_id}', [LessonNoteController::class, 'editLesson']);
            Route::delete('/remove/{lesson_id}', [LessonNoteController::class, 'deleteLesson']);
            Route::patch('/approve/{lesson_id}', [LessonNoteController::class, 'approveLesson']);
            Route::patch('/unapprove/{lesson_id}', [LessonNoteController::class, 'unapproveLesson']);
        });

        // Communication book
        Route::prefix('communicationbook')->controller(CommunicationBookController::class)->group(function () {
            Route::post('/', 'store');
            Route::get('/{class_id}', 'show');
            Route::get('/closed/{class_id}', 'closed');
            Route::post('/{id}/replies', 'replies');
            Route::get('/replies/{id}', 'getReplies');
            Route::patch('/close/{id}', 'close');
            Route::patch('/edit/{id}', 'edit');
            Route::patch('/reply/edit/{id}', 'editReply');
            Route::delete('/reply/delete/{id}', 'deleteReply');
            Route::get('/unread/count', 'unreadCount');
        });

        // Flip class
        Route::prefix('flipclass')->controller(FlipClassController::class)->group(function () {
            Route::post('/add', 'addFlipClass');
            Route::get('/single/{id}/{class_id}/{subject_id}/{week}/{term}/{session}', 'getOneFlipClass')
            ->where('session', '.+');
            Route::get('/{class_id}/{subject_id}/{week}/{term}/{session}', 'getFlipClass')
            ->where('session', '.+');
            Route::patch('/edit/{id}', 'editFlipClass');
            Route::delete('/remove/{id}', 'deleteFlipClass');
            Route::patch('/approve/{id}', 'approveFlipClass');
            Route::patch('/unapprove/{id}', 'unapproveFlipClass');

            Route::prefix('assessment')->controller(FlipClassController::class)->group(function () {
                Route::post('/add-obj', 'addObjAssessment');
                Route::post('/add-theory', 'addTheoryAssessment');
                Route::get('/{id}/{type}', 'getSingleQuestion');
                Route::get('/{period}/{term}/{session}/{flip_class_id}/{type}/{week}/{subject_id}', 'getObjQuestions')
                    ->where('session', '.+');
                Route::patch("/edit-obj", 'editObj');
                Route::patch("/edit-theory", 'editThoery');
                Route::delete("/delete/{id}", 'delAssessment');
                Route::patch("/publish", 'publish');

                // Student Answer
                Route::post('/obj-answer', 'objAnswer');
                Route::post('/theory-answer', 'theoryAnswer');
                Route::get('/answer/{period}/{term}/{session}/{type}/{week}', 'getAnswer')
                ->where('session', '.+');

                // Mark Assessment
                Route::post('/mark', 'mark');
                Route::patch('/update-mark', 'updateMark');
                Route::get('/marked/{period}/{term}/{session}/{type}/{week}', 'marked')
                ->where('session', '.+');
                Route::get('/marked-student/{student_id}/{period}/{term}/{session}/{type}/{week}', 'markedByStudent')
                ->where('session', '.+');

                // Result
                Route::post('/result', 'addResult');
                Route::get('/get-result/{period}/{term}/{session}/{type}/{week}', 'getResult')
                ->where('session', '.+');
                Route::get('/get-student-result/{student_id}/{period}/{term}/{session}/{type}', 'resultStudent')
                ->where('session', '.+');

                // Performance
                Route::get('/performance', 'performanceChart');
            });
        });

    });



