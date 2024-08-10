<?php

namespace App\Observers;

use App\Actions\StoreAuditLog;
use App\Models\IpAddress;

class IpAddressObserver
{
    /**
     * Handle the IpAddress "created" event.
     */
    public function created(IpAddress $ipAddress): void
    {
        StoreAuditLog::run(
            'Created',
            "Added a new IP address: {$ipAddress->ip_address} with label: {$ipAddress->label}",
            [
                'ip_address' => $ipAddress->ip_address,
                'old_label' => $ipAddress->label,
                'new_label' => $ipAddress->label,
            ]
        );
    }

    /**
     * Handle the IpAddress "updated" event.
     */
    public function updated(IpAddress $ipAddress): void
    {
        StoreAuditLog::run(
            'Updated',
            "Edited IP address: {$ipAddress->ip_address}; Old label: {$ipAddress->getOriginal()['label']}; New label: {$ipAddress->getChanges()['label']}",
            [
                'ip_address' => $ipAddress->ip_address,
                'old_label' => $ipAddress->getOriginal()['label'],
                'new_label' => $ipAddress->getChanges()['label'],
            ]
        );
    }
}
