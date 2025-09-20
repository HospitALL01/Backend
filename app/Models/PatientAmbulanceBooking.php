<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientAmbulanceBooking extends Model
{
    use HasFactory;

    // Define the table name if it differs from the plural form
    protected $table = 'patient_ambulance_bookings';

    // Fillable fields for mass assignment
    protected $fillable = [
        'patient_name',
        'hospital_name',
        'driver_name',
        'driver_phone',
        'ambulance_id',
        'status', // 'Booked', 'Cancelled', etc.
        'patient_id', // Added patient_id to track which patient booked
    ];

    // Define the relationship between this model and the Ambulance model
    public function ambulance()
    {
        return $this->belongsTo(Ambulance::class);
    }

    // Define the relationship between this model and the Patient model (if needed)
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
