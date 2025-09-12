<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Review; // Import Review model
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
        $doctor->d_password = Hash::make($req->password); // Encrypt password
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

        // Generate JWT token for the doctor
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

   // Add Review for Doctor by Email
    public function addReview(Request $request, $email)
    {
        // Validate the request data
        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        // Ensure the user is authenticated as a patient
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        // Find doctor by email
        $doctor = Doctor::where('d_email', $email)->first();

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], Response::HTTP_NOT_FOUND);
        }

        // Save the review
        $review = new Review();
        $review->doctor_id = $doctor->id;  // Use the doctor's ID
        $review->patient_id = auth()->user()->id;  // Assuming the patient is logged in
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->patient_name = auth()->user()->name;  // Store patient's name
        $review->save();

        // Update the doctor's rating based on the new review
        $doctor->rating = Review::where('doctor_id', $doctor->id)->avg('rating'); // Update doctor's rating based on reviews
        $doctor->save();

        return response()->json(['message' => 'Review added successfully', 'review' => $review], Response::HTTP_OK);
    }

    // Get Reviews for Doctor by Email
    public function getReviews($email)
    {
        // Find doctor by Email
        $doctor = Doctor::where('d_email', $email)->first(); // Use where() to find by email

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Get the reviews for the doctor
        $reviews = Review::where('doctor_id', $doctor->id)->get();

        return response()->json(['reviews' => $reviews], 200);
    }
}
