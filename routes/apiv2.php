<?php

use App\Http\Controllers\v2\CbtController;
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

    });

});


