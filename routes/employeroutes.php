<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeAuthController;

Route::post('/employe-register', [EmployeAuthController::class, 'register']);
Route::post('/employe-login', [EmployeAuthController::class, 'login']);

Route::middleware('auth:employe_api')->group(function () {
    Route::get('/employe-profile', [EmployeAuthController::class, 'profile']);
    Route::get('/employe-logout', [EmployeAuthController::class, 'logout']);
});
