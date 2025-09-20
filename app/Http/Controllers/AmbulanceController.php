<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use App\Models\PatientAmbulanceBooking;
use Illuminate\Http\Request;

class AmbulanceController extends Controller
{
    /**
     * Method to fetch nearby ambulances based on user's location.
     */
    public function getNearby(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $userLat = $validated['lat'];
        $userLng = $validated['lng'];
        $radius = 25; // km

        // Fetching only available ambulances within a given radius
        $ambulances = Ambulance::select('ambulances.*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians(latitude) ) * cos( radians(longitude) - radians(?) ) + sin( radians(?) ) * sin( radians(latitude) ) ) ) AS distance',
                [$userLat, $userLng, $userLat]
            )
            ->where('status', 'Available')
            ->having('distance', '<', $radius)
            ->orderBy('distance', 'asc')
            ->limit(10)
            ->get();

        return response()->json($ambulances);
    }

    /**
     * Method to request (book) an ambulance.
     */
    public function requestAmbulance(Request $request, $id)
{
    $validated = $request->validate([
        'patient_name' => 'required|string|max:255',
        'patient_id' => 'required|integer', // Validate patient_id
    ]);

    $ambulance = Ambulance::find($id);

    if (!$ambulance || $ambulance->status !== 'Available') {
        return response()->json(['message' => 'Ambulance not available'], 404);
    }

    // Create booking and store patient_id
    $booking = new PatientAmbulanceBooking();
    $booking->patient_name = $validated['patient_name'];
    $booking->hospital_name = $ambulance->hospital_name;
    $booking->driver_name = $ambulance->driver_name;
    $booking->driver_phone = $ambulance->driver_phone;
    $booking->ambulance_id = $ambulance->id;
    $booking->patient_id = $validated['patient_id']; // Store the patient_id
    $booking->status = 'Booked'; // Mark as booked
    $booking->save();

    // Update ambulance status
    $ambulance->status = 'Busy'; // Mark as busy
    $ambulance->save();

    return response()->json([
        'id' => $booking->id,
        'hospital_name' => $booking->hospital_name,
        'driver_name' => $booking->driver_name,
        'driver_phone' => $booking->driver_phone,
        'status' => $booking->status,
    ]);
}
 // Add this method to get the patient's booked ambulance
public function getPatientBookings(Request $request)
{
    $patientId = $request->input('patient_id'); // Get patient_id from the request
    $bookings = PatientAmbulanceBooking::where('patient_id', $patientId)->get(); // Fetch only the bookings made by this patient

    return response()->json($bookings);
}

    /**
     * Method to cancel a booking.
     */
 public function cancelRequest(Request $request, $id)
{
    $validated = $request->validate([
        'patient_id' => 'required|integer', // Ensure patient_id is provided for cancellation
    ]);

    // Find the booking
    $booking = PatientAmbulanceBooking::where('ambulance_id', $id)
        ->where('status', 'Booked')
        ->where('patient_id', $validated['patient_id'])
        ->first();

    if (!$booking) {
        return response()->json(['message' => 'No active booking found or you are not authorized to cancel this booking'], 404);
    }

    // Delete the booking record
    $booking->delete();

    // Find the ambulance and mark it as available
    $ambulance = Ambulance::find($id);
    if ($ambulance) {
        $ambulance->status = 'Available'; // Mark as available
        $ambulance->save();
    }

    return response()->json(['message' => 'Booking cancelled successfully']);
}



    /**
     * Method to fetch nearby ambulances based on user's location (additional functionality).
     */
    public function nearbyAmbulances(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
        ]);

        $latitude = $validated['lat'];
        $longitude = $validated['lng'];
        $radius = 25; // Kilometers

        $ambulances = Ambulance::selectRaw("
            id,
            hospital_name,
            driver_name,
            driver_phone,
            status,
            ( 6371 * acos( cos( radians(?) ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(?) ) + sin( radians(?) ) * sin( radians( lat ) ) ) ) AS distance
        ", [$latitude, $longitude, $latitude])
        ->having('distance', '<', $radius)
        ->orderBy('distance')
        ->get();

        // Get all active bookings to determine current status for *this* user
        $activeBookings = PatientAmbulanceBooking::where('status', 'booked')->get()->keyBy('ambulance_id');

        // Simulate patient's active bookings from frontend
        $patient_id_from_frontend = $request->header('X-Patient-ID'); // Or from authenticated user

        $myActiveBookings = PatientAmbulanceBooking::where('patient_id', $patient_id_from_frontend)
            ->where('status', 'booked')
            ->get()
            ->keyBy('ambulance_id');

        // Update ambulance statuses based on bookings
        $ambulances->each(function ($ambulance) use ($activeBookings, $myActiveBookings, $patient_id_from_frontend) {
            $booking = $activeBookings->get($ambulance->id);

            if ($booking) {
                if ($myActiveBookings->has($ambulance->id)) {
                    $ambulance->current_booking_status = 'BookedByMe';
                    $ambulance->driver_name = $booking->driver_name;
                    $ambulance->driver_phone = $booking->driver_phone;
                } else {
                    $ambulance->current_booking_status = 'BookedByOther';
                    $ambulance->driver_name = null;
                    $ambulance->driver_phone = null;
                }
            } else {
                $ambulance->current_booking_status = 'Available';
            }
        });

        return response()->json($ambulances);
    }
}
