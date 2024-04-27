<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\CbtAddAnswerRequest;
use App\Http\Requests\v2\CbtAddQuestionRequest;
use App\Http\Requests\v2\CbtSetupRequest;
use App\Services\Cbt\CbtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CbtController extends Controller
{
    public $cbt;

    public function __construct()
    {
        $this->cbt = new CbtService;
    }

    public function addSetup(CbtSetupRequest $request)
    {
        $user = Auth::user();
        return $this->cbt->setup($user, $request);
    }

    public function getSetup(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getSettings($user, $request);
    }

    public function addQuestion(CbtAddQuestionRequest $request)
    {
        $user = Auth::user();
        return $this->cbt->addCbtQuestion($user, $request);
    }

    public function getQuestions(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getAllQuestions($user, $request);
    }

    public function editQuestion(Request $request, $id)
    {
        $user = Auth::user();
        return $this->cbt->updateQuestion($user, $request, $id);
    }

    public function deleteQuestion($id)
    {
        $user = Auth::user();
        return $this->cbt->removeQuestion($user, $id);
    }

    public function addAnswer(CbtAddAnswerRequest $request)
    {
        $user = Auth::user();
        return $this->cbt->createCbtAnswer($user, $request);
    }

    public function getAnswerSubject(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getAnswerSubject($user, $request);
    }

    public function getAnswerStudent(Request $request)
    {
        dd($request);
        $user = Auth::user();
        return $this->cbt->getAnswerOneStudent($user, $request);
    }
}
