<?php

use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\EmployeAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Admin Routes 

require __DIR__ . '/adminroutes.php';

require __DIR__ . '/employeroutes.php';
