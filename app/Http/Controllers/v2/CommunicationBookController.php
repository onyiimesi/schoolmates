<?php

namespace App\Http\Controllers\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\v2\CommunicationBookReplyRequest;
use App\Http\Requests\v2\CommunicationBookRequest;
use App\Services\CommunicationBook\CommunicationBookService;
use Illuminate\Http\Request;

class CommunicationBookController extends Controller
{
    protected $service;

    public function __construct(CommunicationBookService $communicationBookService)
    {
        $this->service = $communicationBookService;
    }

    public function store(CommunicationBookRequest $request)
    {
        return $this->service->store($request);
    }

    public function show()
    {
        return $this->service->show();
    }

    public function replies(CommunicationBookReplyRequest $request, $id)
    {
        return $this->service->replies($request, $id);
    }

    public function getReplies($id)
    {
        return $this->service->getReplies($id);
    }

    public function closed()
    {
        return $this->service->closed();
    }

    public function close($id)
    {
        return $this->service->close($id);
    }
}
