<?php

use App\Http\Controllers\Api\AuditLogController;
use App\Http\Controllers\Api\IpAddressController;
use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'store')->middleware('guest')->name('api.user.authenticate');
        Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('api.user.logout');
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::controller(IpAddressController::class)->group(function () {
            Route::get('/ip-address', 'index')->name('api.ip_address.index');
            Route::post('/ip-address', 'store')->name('api.ip_address.store');
            Route::patch('/ip-address/{ipAddress}', 'update')->name('api.ip_address.update');
        });

        Route::get('/audit-logs', [AuditLogController::class, 'index'])->name('api.audit_logs.index');
    });
});