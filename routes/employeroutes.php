<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeAuthController;

// Employe Login
Route::post('/employe-login', [EmployeAuthController::class, 'login']);
// Employe OTP Login

Route::post('/employe-otp-login', [EmployeAuthController::class, 'otp_login']);

// Employe OTP  Verify And Login 
Route::post('/employe-otp-verify', [EmployeAuthController::class, 'otp_verify_login']);
// Middleware For Auth user 

Route::middleware('auth:employe_api')->group(function () {
    Route::get('/employe-profile', [EmployeAuthController::class, 'profile']);
    Route::get('/employe-logout', [EmployeAuthController::class, 'logout']);
});
