<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Booking;
use App\Models\Doctor;
use App\Models\Patient;
use App\Controllers\DoctorInfoController;
use App\Controllers\PatientController;
use App\Controllers\DoctorController;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        // doctor: id বা email যেকোনো একটি আবশ্যক
        $validator = Validator::make($request->all(), [
            'doctor_id'        => 'nullable|integer|exists:doctors,id',
            'doctor_email'     => 'nullable|email',
            'doctor_name'      => 'nullable|string|max:255',

            'patient_id'       => 'required|integer|exists:patients,id',
            'patient_name'     => 'nullable|string|max:255',

            'appointment_date' => 'required|date',
            'payment_method'   => 'required|string|max:255',
            'fees'             => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 422);
        }

        // --- Resolve Doctor ---
        $doctor = null;
        if ($request->filled('doctor_id')) {
            $doctor = Doctor::find($request->doctor_id);
        } elseif ($request->filled('doctor_email')) {
            $doctor = Doctor::where('d_email', $request->doctor_email)->first();
        }

        if (!$doctor) {
            return response()->json([
                'message' => 'Doctor not found. Provide a valid doctor_id or doctor_email.'
            ], 422);
        }

        // --- Resolve Patient ---
        $patient = Patient::find($request->patient_id);
        if (!$patient) {
            return response()->json(['message' => 'Patient not found.'], 422);
        }

        // Prefer request names; fallback to DB
        $doctorName  = $request->doctor_name  ?: ($doctor->d_name ?? null);
        $patientName = $request->patient_name ?: ($patient->p_name ?? null);

        if (!$doctorName || !$patientName) {
            return response()->json(['message' => 'Doctor or Patient name missing.'], 422);
        }

        $booking = Booking::create([
            'doctor_id'        => $doctor->id,
            'doctor_name'      => $doctorName,
            'patient_id'       => $patient->id,
            'patient_name'     => $patientName,
            'appointment_date' => $request->appointment_date,
            'payment_method'   => $request->payment_method,
            'fees'             => $request->fees,
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Booking created successfully',
            'data'    => $booking
        ], 201);
    }
}
