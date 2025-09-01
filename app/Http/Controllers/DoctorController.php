<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class DoctorController extends Controller
{
    // Register Doctor
    public function register(Request $req)
    {
        // Check if email exists in doctors table
        if (Doctor::where('d_email', $req->email)->exists()) {
            return response()->json(['error' => 'Email already exists']);
        }

        // Create new doctor
        $doctor = new Doctor;
        $doctor->d_name = $req->fullname;
        $doctor->d_email = $req->email;
        $doctor->d_phone = $req->phone;
        $doctor->d_password = Hash::make($req->password);
        $doctor->save();

        return response()->json(['message' => 'Doctor registered successfully', 'doctor' => $doctor]);
    }

    // Login Doctor
    public function login(Request $req)
    {
        $doctor = Doctor::where('d_email', $req->email)->first();

        if (!$doctor || !Hash::check($req->password, $doctor->d_password)) {
            return response()->json(['error' => 'Email or password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        // Generate JWT token (optional, if you want to use JWT for doctors)
        $token = JWTAuth::claims(['role' => 'doctor'])->fromUser($doctor);

        return response()->json([
            'message' => 'Login successful',
            'doctor'  => $doctor,
            'token'   => $token
        ], Response::HTTP_OK);
    }

    // Check if email already exists
    public function checkEmail(Request $req)
    {
        $exists = Doctor::where('d_email', $req->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}
