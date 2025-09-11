<?php

namespace App\Http\Controllers;

use App\Models\Ambulance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // ✅ 1. IMPORT the DB facade for raw queries

class AmbulanceController extends Controller
{
    /**
     * Method to fetch nearby ambulances based on user's location.
     */
    public function getNearby(Request $request)
    {
        // ✅ 2. VALIDATE that the frontend sent the required lat and lng
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $userLat = $validated['lat'];
        $userLng = $validated['lng'];
        
        // Radius in kilometers to search for ambulances
        $radius = 25; // You can adjust this value

        // ✅ 3. THE HAVERSINE FORMULA IN A RAW SQL QUERY
        // This calculates the distance in kilometers between the user and each ambulance.
        $ambulances = Ambulance::select('ambulances.*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians(?) ) + sin( radians(?) ) * sin( radians( latitude ) ) ) ) AS distance',
                [$userLat, $userLng, $userLat]
            )
            ->where('status', '=', 'Available')
            ->having('distance', '<', $radius)
            ->orderBy('distance', 'asc')
            ->limit(4)
            ->get();

        return response()->json($ambulances);
    }

    /**
     * Method to request/book an ambulance.
     * (This method remains unchanged)
     */
    public function requestAmbulance(Request $request, $id)
    {
        $ambulance = Ambulance::find($id);

        if (!$ambulance || $ambulance->status !== 'Available') {
            return response()->json(['message' => 'Ambulance not available'], 404);
        }

        $ambulance->status = 'Busy';
        $ambulance->save();

        return response()->json($ambulance);
    }

    /**
     * Method to cancel a booking.
     * (This method remains unchanged)
     */
    public function cancelRequest(Request $request, $id)
    {
        $ambulance = Ambulance::find($id);

        if (!$ambulance) {
            return response()->json(['message' => 'Ambulance not found'], 404);
        }

        $ambulance->status = 'Available';
        $ambulance->save();

        return response()->json(['message' => 'Booking cancelled successfully']);
    }
}