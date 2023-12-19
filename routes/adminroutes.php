<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AllEmployeeController;
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

    
    // Admin Logout
    Route::get('/admin-logout', [AdminAuthController::class, 'logout']);
});

// Employe Register
Route::post('/admin-employe-register', [EmployeeRegistrationController::class, 'registration']);
// Get All District
Route::get('/admin-get-districts', [EmployeeRegistrationController::class, 'getDistricts']);
// Get All Block By District Code 
Route::post('/admin-get-blocks', [EmployeeRegistrationController::class, 'getBlocks']);
//Get all  GP by District & Block Route
Route::post('/admin-get-gps', [EmployeeRegistrationController::class, 'getGPs']);
// Get All Designations
Route::get('/admin-get-designations', [EmployeeRegistrationController::class, 'getDesignations']);
// Get All Branches
Route::get('/admin-get-branches', [EmployeeRegistrationController::class, 'getBranches']);
// View All Registered Employees 
Route::get('/admin-all-employees', [AllEmployeeController::class, 'allEmployees']);
// View Specific Employee Details
Route::post('/admin-view_employee', [AllEmployeeController::class, 'viewEmployee']);