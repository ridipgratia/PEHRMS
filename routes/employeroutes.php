<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeAuthController;

// Employe Register
Route::post('/employe-register', [EmployeAuthController::class, 'register']);
// Employe Login
Route::post('/employe-login', [EmployeAuthController::class, 'login']);
// Employe OTP Login

Route::post('/employe-otp-login', [EmployeAuthController::class, 'otp_login']);

// Middleware For Auth user 

Route::middleware('auth:employe_api')->group(function () {
    Route::get('/employe-profile', [EmployeAuthController::class, 'profile']);
    Route::get('/employe-logout', [EmployeAuthController::class, 'logout']);
});
