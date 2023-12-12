<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\EmployeeRegistrationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Admin open Routes 

Route::post('/admin-register', [AdminAuthController::class, 'register']);
Route::post('/admin-login', [AdminAuthController::class, 'login']);
// Admin Protected 

Route::middleware('auth:admin_api')->group(function () {
    // Admin Profile
    Route::get('/admin-profile', [AdminAuthController::class, 'profile']);
    // Employe Register
    Route::post('/admin-employe-register', [EmployeeRegistrationController::class, 'register']);
    // Get All District
    Route::get('/admin-get-districts', [EmployeeRegistrationController::class, 'getDistricts']);
    // Get All Block By District Code 
    Route::post('/admin-get-blocks', [EmployeeRegistrationController::class, 'getBlocks']);
    //Get all  GP by District & Block Route
    Route::post('/admin-get-gps', [EmployeeRegistrationController::class, 'getGPs']);
    // Admin Logout
    Route::get('/admin-logout', [AdminAuthController::class, 'logout']);
});
