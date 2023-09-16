<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditLogController extends Controller
{
    public function getAudit(){

        $article = AuditLogResource::collection(Audit::get());

        return [
            'status' => 'true',
            'message' => 'Audits',
            'data' => $article
        ];

    }
}
