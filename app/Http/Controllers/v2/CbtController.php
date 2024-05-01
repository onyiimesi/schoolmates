<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\CbtAddAnswerRequest;
use App\Http\Requests\v2\CbtAddQuestionRequest;
use App\Http\Requests\v2\CbtPublishRequest;
use App\Http\Requests\v2\CbtResultRequest;
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

    public function getStudentAnswer(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getAnswerOneStudent($user, $request);
    }

    public function publish(CbtPublishRequest $request)
    {
        $user = Auth::user();
        return $this->cbt->cbtPublish($user, $request);
    }

    public function addResult(CbtResultRequest $request)
    {
        $user = Auth::user();
        return $this->cbt->createResult($user, $request);
    }

    public function getResultStudent(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getStudentResult($user, $request);
    }

    public function performance(Request $request)
    {
        $user = Auth::user();
        return $this->cbt->getChart($user, $request);
    }
}
