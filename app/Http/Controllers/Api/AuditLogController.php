<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuditLogResource;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AuditLogController extends Controller
{
    // Audit Log lists
    public function index(): AnonymousResourceCollection
    {
        return AuditLogResource::collection(AuditLog::latest('id')->paginate(20, ['*'], 'audit_logs'));
    }
}
