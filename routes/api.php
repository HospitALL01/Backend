<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorInfoController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/check-email', [UserController::class, 'checkEmail']);

Route::post('/patient/register', [PatientController::class, 'register']);
Route::post('/patient/login', [PatientController::class, 'login']);
Route::post('/patient/check-email', [PatientController::class, 'checkEmail']);

Route::post('/doctor/register', [DoctorController::class, 'register']);
Route::post('/doctor/login', [DoctorController::class, 'login']);
Route::post('/doctor/check-email', [DoctorController::class, 'checkEmail']);

// Doctor info CRUD (used by Profile_Doctor & AdminDashboard)
Route::get('/doctor-info', [DoctorInfoController::class, 'index']);        // ✅ NEW: all doctors
Route::get('/doctor-info/{email}', [DoctorInfoController::class, 'show']);  // ✅ NEW: doctor by email
Route::post('/doctor-info', [DoctorInfoController::class, 'store']);
Route::put('/doctor-info/{email}', [DoctorInfoController::class, 'update']);
