<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditLogResource;
use Illuminate\Http\Request;
use OwenIt\Auditing\Models\Audit;

class AuditLogController extends Controller
{
    /**
     * @return array<string, mixed>
     */
    public function getAudit(): array
    {
        $article = AuditLogResource::collection(Audit::get());

        return [
            'status' => 'true',
            'message' => 'Audits',
            'data' => $article
        ];
    }
}
