<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorInfoController;
use App\Http\Controllers\AmbulanceController;
use App\Http\Controllers\PaymentController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
Route::post('/check-email', [UserController::class, 'checkEmail']);

// Patient Routes
Route::post('/patient/register', [PatientController::class, 'register']);
Route::post('/patient/login', [PatientController::class, 'login']);
Route::post('/patient/check-email', [PatientController::class, 'checkEmail']);

// Doctor Routes
Route::post('/doctor/register', [DoctorController::class, 'register']);
Route::post('/doctor/login', [DoctorController::class, 'login']);
Route::post('/doctor/check-email', [DoctorController::class, 'checkEmail']);

// Ambulance Routes
Route::get('/ambulances/nearby', [AmbulanceController::class, 'getNearby']);
Route::post('/ambulances/{id}/request', [AmbulanceController::class, 'requestAmbulance']);
Route::post('/ambulances/{id}/cancel', [AmbulanceController::class, 'cancelRequest']);

// Doctor Info Routes
Route::get('/doctor-info', [DoctorInfoController::class, 'index']);        // List all doctors
Route::get('/doctor-info/{email}', [DoctorInfoController::class, 'show']);  // Get doctor info by email
Route::post('/doctor-info', [DoctorInfoController::class, 'store']);       // Add new doctor
Route::put('/doctor-info/{email}', [DoctorInfoController::class, 'update']); // Update doctor info by email

// Review Routes for Doctor by Email
// Route::post('/doctor-info/{email}/review', [DoctorController::class, 'addReview']); 
// Route::get('/doctor-info/{email}/reviews', [DoctorController::class, 'getReviews']); 


Route::middleware('auth:api')->group(function () {
    Route::post('/doctor-info/{email}/review', [DoctorController::class, 'addReview']);
    Route::get('/doctor-info/{email}/reviews', [DoctorController::class, 'getReviews']);
});

Route::post('/payment', [PaymentController::class, 'processPayment']);
