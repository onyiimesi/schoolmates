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

    public function show($classId)
    {
        return $this->service->show($classId);
    }

    public function replies(CommunicationBookReplyRequest $request, $id)
    {
        return $this->service->replies($request, $id);
    }

    public function getReplies($id)
    {
        return $this->service->getReplies($id);
    }

    public function closed($classId)
    {
        return $this->service->closed($classId);
    }

    public function close($id)
    {
        return $this->service->close($id);
    }

    public function edit(Request $request, $id)
    {
        return $this->service->edit($request, $id);
    }

    public function editReply(Request $request, $id)
    {
        return $this->service->editReply($request, $id);
    }

    public function deleteReply($id)
    {
        return $this->service->deleteReply($id);
    }

    public function unreadCount()
    {
        return $this->service->unreadCount();
    }
}
