<?php

namespace App\Actions;

use App\Actions\Traits\AsObject;
use App\Models\AuditLog;

class StoreAuditLog
{
    use AsObject;

    /**
     * Store audit log action
     */
    public function handle(string $action, string $description, array $data): void
    {
        AuditLog::create([
            'action' => $action,
            'description' => $description,
            'details' => json_encode([
                'user_ip_address' => $this->getIpAddress(),
                'user_agent' => request()->header('User-Agent'),
                'data' => $data,
            ]),
            'user_id' => auth()->user()->id,
        ]);
    }

    // Get user ip address
    protected function getIpAddress(): string
    {
        return (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
            ? $_SERVER['HTTP_CF_CONNECTING_IP']
            : request()->ip();
    }
}
