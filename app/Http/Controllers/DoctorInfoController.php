<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorInfo;

class DoctorInfoController extends Controller
{
    /**
     * Return all doctors (for Admin dashboard).
     * GET /api/doctor-info
     */
    public function index()
    {
        // Fetch all doctors ordered by created_at (latest first)
        $doctors = DoctorInfo::orderBy('created_at', 'desc')->get();

        // Map data to send as response
        $mapped = $doctors->map(function ($d) {
            return [
                'doctorName'        => $d->doctor_name,
                'gender'            => $d->gender,
                'nationality'       => $d->nationality,
                'specialization'    => $d->specialization,
                'licenseNumber'     => $d->license_number,
                'licenseIssueDate'  => $d->license_issue_date,
                'hospitalName'      => $d->hospital_name,
                'yearsOfExperience' => $d->years_of_experience,
                'phone'             => $d->phone,
                'email'             => $d->email,
                'currentPosition'   => $d->current_position,
                'previousPositions' => $d->previous_positions,
                'created_at'        => $d->created_at,
                'updated_at'        => $d->updated_at,
            ];
        });

        return response()->json(['data' => $mapped], 200);
    }

    /**
     * Return a single doctor by email.
     * GET /api/doctor-info/{email}
     */
    public function show($email)
    {
        $doctorInfo = DoctorInfo::where('email', $email)->first();

        if (!$doctorInfo) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Prepare the response data
        $payload = [
            'doctorName'        => $doctorInfo->doctor_name,
            'gender'            => $doctorInfo->gender,
            'nationality'       => $doctorInfo->nationality,
            'specialization'    => $doctorInfo->specialization,
            'licenseNumber'     => $doctorInfo->license_number,
            'licenseIssueDate'  => $doctorInfo->license_issue_date,
            'hospitalName'      => $doctorInfo->hospital_name,
            'yearsOfExperience' => $doctorInfo->years_of_experience,
            'phone'             => $doctorInfo->phone,
            'email'             => $doctorInfo->email,
            'currentPosition'   => $doctorInfo->current_position,
            'previousPositions' => $doctorInfo->previous_positions,
            'created_at'        => $doctorInfo->created_at,
            'updated_at'        => $doctorInfo->updated_at,
        ];

        return response()->json(['data' => $payload], 200);
    }

    /**
     * Store doctor information in the database.
     * POST /api/doctor-info
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'doctorName' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'specialization' => 'required|string|max:255',
            'licenseNumber' => 'required|string|max:50',
            'licenseIssueDate' => 'required|date',
            'hospitalName' => 'required|string|max:255',
            'yearsOfExperience' => 'required|integer',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'currentPosition' => 'nullable|string|max:255',
            'previousPositions' => 'nullable|string|max:255',
        ]);

        // Check if doctor with the same email exists
        $existingDoctor = DoctorInfo::where('email', $validatedData['email'])->first();
        if ($existingDoctor) {
            return response()->json(['message' => 'Doctor with this email already exists'], 400);
        }

        // Create and store new doctor info
        $doctorInfo = new DoctorInfo();
        $doctorInfo->doctor_name = $validatedData['doctorName'];
        $doctorInfo->gender = $validatedData['gender'];
        $doctorInfo->nationality = $validatedData['nationality'];
        $doctorInfo->specialization = $validatedData['specialization'];
        $doctorInfo->license_number = $validatedData['licenseNumber'];
        $doctorInfo->license_issue_date = $validatedData['licenseIssueDate'];
        $doctorInfo->hospital_name = $validatedData['hospitalName'];
        $doctorInfo->years_of_experience = $validatedData['yearsOfExperience'];
        $doctorInfo->phone = $validatedData['phone'];
        $doctorInfo->email = $validatedData['email'];
        $doctorInfo->current_position = $validatedData['currentPosition'];
        $doctorInfo->previous_positions = $validatedData['previousPositions'];
        $doctorInfo->save();

        return response()->json(['message' => 'Doctor information submitted successfully'], 200);
    }

    /**
     * Update doctor information in the database.
     * PUT /api/doctor-info/{email}
     */
    public function update(Request $request, $email)
    {
        // Fetch doctor by email
        $doctorInfo = DoctorInfo::where('email', $email)->first();
        if (!$doctorInfo) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        // Validate incoming request
        $validatedData = $request->validate([
            'doctorName' => 'required|string|max:255',
            'gender' => 'required|string|max:20',
            'nationality' => 'required|string|max:100',
            'specialization' => 'required|string|max:255',
            'licenseNumber' => 'required|string|max:50',
            'licenseIssueDate' => 'required|date',
            'hospitalName' => 'required|string|max:255',
            'yearsOfExperience' => 'required|integer',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|max:255',
            'currentPosition' => 'nullable|string|max:255',
            'previousPositions' => 'nullable|string|max:255',
        ]);

        // Update the doctor's information
        $doctorInfo->doctor_name = $validatedData['doctorName'];
        $doctorInfo->gender = $validatedData['gender'];
        $doctorInfo->nationality = $validatedData['nationality'];
        $doctorInfo->specialization = $validatedData['specialization'];
        $doctorInfo->license_number = $validatedData['licenseNumber'];
        $doctorInfo->license_issue_date = $validatedData['licenseIssueDate'];
        $doctorInfo->hospital_name = $validatedData['hospitalName'];
        $doctorInfo->years_of_experience = $validatedData['yearsOfExperience'];
        $doctorInfo->phone = $validatedData['phone'];
        $doctorInfo->email = $validatedData['email'];
        $doctorInfo->current_position = $validatedData['currentPosition'];
        $doctorInfo->previous_positions = $validatedData['previousPositions'];
        $doctorInfo->save();

        return response()->json(['message' => 'Doctor information updated successfully', 'data' => $doctorInfo], 200);
    }
}
