<?php

use App\Http\Controllers\Api\LoginController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::controller(LoginController::class)->group(function () {
        Route::post('/login', 'store')->middleware('guest')->name('api.user.authenticate');
        Route::post('/logout', 'logout')->middleware('auth:sanctum')->name('api.user.logout');
    });
});