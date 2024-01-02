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
// Get All Service Status
Route::get('/admin-get-service-status', [EmployeeRegistrationController::class, 'getServices']);
// Get All Caste 
Route::get('/admin-get-caste', [EmployeeRegistrationController::class, 'getCaste']);
// View All Registered Employees 
Route::get('/admin-all-employees', [AllEmployeeController::class, 'allEmployees']);
// View Specific Employee Details
Route::post('/admin-view-employee', [AllEmployeeController::class, 'getAllEmployeeDetails']);
// Search On One Input 
Route::post('/admin-search-one-input', [AllEmployeeController::class, 'searchOnOneInput']);
// Search By Many Select Options
Route::post('/search-by-many-select', [AllEmployeeController::class, 'searchByManySelect']);
// Get designation by level id
Route::post('admin-designation-by-level', [EmployeeRegistrationController::class, 'getDesignationByLevel']);
// Export Employees Data Excel
Route::post('/export-employee-excel', [AllEmployeeController::class, 'exportEmployeeExcel']);
// Export Employees Data CSV
Route::post('/export-employee-csv', [AllEmployeeController::class, 'exportEmployeeCSV']);
// Export Employees Data PDF
Route::post('/export-employee-pdf', [AllEmployeeController::class, 'exportEmployeePDF']);
// Order By Filter 
Route::post('/order-by-filter', [AllEmployeeController::class, 'orderByFilter']);
// Get Total Count Of Register Employees 
Route::get('/total-register-employee', [AllEmployeeController::class, 'totalRegisterEmployee']);
