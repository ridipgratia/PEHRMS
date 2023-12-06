<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AdminLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Admin open Routes 

Route::post('/admin-register', [AdminAuthController::class, 'register']);
Route::post('/admin-login', [AdminAuthController::class, 'login']);
// Admin Protected 

Route::middleware('auth:admin_api')->group(function () {
    Route::get('/admin-profile', [AdminAuthController::class, 'profile']);
    Route::get('/admin-logout', [AdminAuthController::class, 'logout']);
});
