<?php

use App\Http\Controllers\v2\CbtController;
use App\Http\Controllers\v2\CommunicationBookController;
use App\Http\Controllers\v2\LessonNoteController;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['middleware' => ['auth:sanctum']], function(){

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

});


