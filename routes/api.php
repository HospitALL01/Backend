<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;  

use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorInfoController;

/*
|----------------------------------------------------------------------
| API Routes
|----------------------------------------------------------------------
|
| Here is where you can register API routes for your application.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Use UserController in the routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/check-email', [UserController::class, 'checkEmail']);

Route::post('/patient/register', [PatientController::class, 'register']);
Route::post('/patient/login', [PatientController::class, 'login']);
Route::post('/patient/check-email', [PatientController::class, 'checkEmail']);

// Doctor routes

Route::post('/doctor/register', [DoctorController::class, 'register']);
Route::post('/doctor/login', [DoctorController::class, 'login']);
Route::post('/doctor/check-email', [DoctorController::class, 'checkEmail']);

Route::post('/doctor-info', [DoctorInfoController::class, 'store']);
