<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorInfoController;
use App\Http\Controllers\AmbulanceController; // Add this import


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

//Route::post('/doctor-info', [DoctorInfoController::class, 'store']); // For new records
//Route::put('/doctor-info/{email}', [DoctorInfoController::class, 'update']); // Update route (by email) (PUT)
Route::get('/ambulances/nearby', [AmbulanceController::class, 'getNearby']);
Route::post('/ambulances/{id}/request', [AmbulanceController::class, 'requestAmbulance']);
Route::post('/ambulances/{id}/cancel', [AmbulanceController::class, 'cancelRequest']);
// Doctor info CRUD (used by Profile_Doctor & AdminDashboard)
Route::get('/doctor-info', [DoctorInfoController::class, 'index']);        // ✅ NEW: all doctors
Route::get('/doctor-info/{email}', [DoctorInfoController::class, 'show']);  // ✅ NEW: doctor by email
Route::post('/doctor-info', [DoctorInfoController::class, 'store']);
Route::put('/doctor-info/{email}', [DoctorInfoController::class, 'update']);
