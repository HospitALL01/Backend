<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Review;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class DoctorController extends Controller
{
    // POST /api/doctor/register
    public function register(Request $req)
    {
        // 🔹 Normalize keys (frontend থেকে যেভাবেই আসুক)
        $req->merge([
            'email' => $req->input('d_email', $req->input('email')),
            'name'  => $req->input('d_name',  $req->input('doctor_name', $req->input('fullname', $req->input('name')))),
            'phone' => $req->input('d_phone', $req->input('doctor_phone', $req->input('phone'))),
            'password' => $req->input('password'),
        ]);

        try {
            // 🔹 Validate
            $validated = $req->validate([
                'name'     => 'required|string|max:255',
                'email'    => 'required|email|max:255',
                'phone'    => 'required|string|min:11|max:20',
                'password' => 'required|string|min:6',
            ]);

            // 🔹 Unique across doctors & patients
            $existsDoctor  = Doctor::where('d_email', $validated['email'])->exists();
            $existsPatient = Patient::where('p_email', $validated['email'])->exists();
            if ($existsDoctor || $existsPatient) {
                return response()->json([
                    'message' => 'Validation failed',
                    'errors'  => ['email' => ['This email is already registered.']],
                ], 422);
            }

            // 🔹 Create (Model mutator d_password hash করবে)
            $doctor = Doctor::create([
                'd_name'     => $validated['name'],
                'd_email'    => $validated['email'],
                'd_phone'    => $validated['phone'],
                'd_password' => $validated['password'], // will be hashed by mutator
            ]);

            return response()->json([
                'message' => 'Doctor registered successfully',
                'doctor'  => $doctor,
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $e->errors(),
            ], 422);
        } catch (QueryException $e) {
            // Column mismatch/constraint → 500 হলেও বিস্তারিত বার্তা দিব
            return response()->json([
                'message' => 'Database error',
                'error'   => $e->getMessage(),
            ], 500);
        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Unexpected error',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    // POST /api/doctor/login
    public function login(Request $req)
    {
        $doctor = Doctor::where('d_email', $req->email)->first();

        if (!$doctor || !Hash::check($req->password, $doctor->d_password)) {
            return response()->json(['error' => 'Email or password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        // JWT
        $token = JWTAuth::claims(['role' => 'doctor'])->fromUser($doctor);

        return response()->json([
            'message' => 'Login successful',
            'doctor'  => $doctor,
            'token'   => $token
        ], Response::HTTP_OK);
    }

    // POST /api/doctor/check-email
    public function checkEmail(Request $req)
    {
        $email = $req->input('email', $req->input('d_email'));
        $exists = Doctor::where('d_email', $email)->exists();
        return response()->json(['exists' => $exists]);
    }

    // GET /api/doctor-info/{email}  → Doctor public info + avg rating (frontend DoctorProfile/Booking)
    public function getDoctorByEmail($email)
    {
        $doctor = Doctor::where('d_email', $email)->first();
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $reviews = Review::where('doctor_id', $doctor->id)->orderBy('id', 'desc')->get();
        $avg     = Review::where('doctor_id', $doctor->id)->avg('rating');

        // যদি doctor_info টেবিল থাকে (optional), সেখান থেকে enrichment আনতে পারো
        // নাহলে অন্তত প্রয়োজনীয় public ফিল্ড পাঠাচ্ছি
        return response()->json([
            'data' => [
                'id'               => $doctor->id,
                'doctorName'       => $doctor->d_name,
                'email'            => $doctor->d_email,
                'phone'            => $doctor->d_phone,
                'specialization'   => null,   // চাইলে doctor_info থেকে map করো
                'hospitalName'     => null,
                'yearsOfExperience'=> null,
                'rating'           => $avg ? round($avg, 1) : null,
                'reviewsCount'     => $reviews->count(),
                'reviews'          => $reviews,
            ]
        ], 200);
    }

    // POST /api/doctor-info/{email}/review  (patient auth দরকার)
    public function addReview(Request $request, $email)
    {
        $request->validate([
            'rating'  => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000',
        ]);

        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $doctor = Doctor::where('d_email', $email)->first();
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], Response::HTTP_NOT_FOUND);
        }

        $user = auth()->user();
        // NOTE: যদি patient model এ name ফিল্ড 'p_name' হয়, তাহলে নিচেরটা টিউন করো
        $patientName = $user->name ?? $user->p_name ?? 'Patient';

        $review = new Review();
        $review->doctor_id   = $doctor->id;
        $review->patient_id  = $user->id;
        $review->rating      = $request->rating;
        $review->comment     = $request->comment;
        $review->patient_name= $patientName;
        $review->save();

        // (optional) avg rating আপডেট করতে চাইলে আলাদা কলামে রাখো; নাহলে প্রয়োজন নেই
        return response()->json(['message' => 'Review added successfully', 'review' => $review], 200);
    }

    // GET /api/doctor-info/{email}/reviews
    public function getReviews($email)
    {
        $doctor = Doctor::where('d_email', $email)->first();
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        $reviews = Review::where('doctor_id', $doctor->id)->orderBy('id', 'desc')->get();
        return response()->json(['reviews' => $reviews], 200);
    }
}
