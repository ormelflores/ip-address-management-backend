<?php

namespace App\Observers;

use App\Actions\StoreAuditLog;
use hisorange\BrowserDetect\Parser as Browser;

class UserActivityObserver
{
    /**
     * Handle the Login event.
     */
    public function login(): void
    {
        StoreAuditLog::run(
            'Login',
            'Login using '.Browser::browserFamily().' on '.Browser::platformFamily(),
            []
        );
    }

    /**
     * Handle the Logout event.
     */
    public function logout(): void
    {
        StoreAuditLog::run(
            'Logout',
            'Logout from '.Browser::browserFamily().' on '.Browser::platformFamily(),
            []
        );
    }
}
