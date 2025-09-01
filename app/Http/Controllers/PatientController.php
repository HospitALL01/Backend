<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class PatientController extends Controller
{
    // Register Patient
    public function register(Request $req)
    {
        // Check if email exists in patients table
        if (Patient::where('p_email', $req->email)->exists()) {
            return response()->json(['error' => 'Email already exists']);
        }

        // Create new patient
        $patient = new Patient;
        $patient->p_name = $req->fullname;
        $patient->p_email = $req->email;
        $patient->p_phone = $req->phone;
        $patient->p_password = Hash::make($req->password);
        $patient->save();

        return response()->json(['message' => 'Patient registered successfully', 'patient' => $patient]);
    }

    // Login Patient
    public function login(Request $req)
    {
        $patient = Patient::where('p_email', $req->email)->first();

        if (!$patient || !Hash::check($req->password, $patient->p_password)) {
            return response()->json(['error' => 'Email or password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        // Generate JWT token (optional, if you want to use JWT for patients)
        $token = JWTAuth::claims(['role' => 'patient'])->fromUser($patient);

        return response()->json([
            'message' => 'Login successful',
            'patient' => $patient,
            'token'   => $token
        ], Response::HTTP_OK);
    }

    // Check if email already exists
    public function checkEmail(Request $req)
    {
        $exists = Patient::where('p_email', $req->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}
