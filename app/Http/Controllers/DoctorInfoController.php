<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DoctorInfo;

class DoctorInfoController extends Controller
{
    /**
     * Store doctor information in the database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate incoming data
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

        // Create a new doctor entry
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

        // Save the doctor information to the database
        $doctorInfo->save();

        // Return a success response
        return response()->json(['message' => 'Doctor information submitted successfully'], 200);
    }
}
