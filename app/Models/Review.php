<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // Define the fillable properties for mass assignment
    protected $fillable = [
        'doctor_id', 
        'patient_id', 
        'rating', 
        'comment', 
        'doctor_name',  // Include doctor_name in the fillable array
        'patient_name'  // Include patient_name in the fillable array
    ];

    // Define the relationship between Review and Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);  // Each review belongs to a doctor
    }

    // Define the relationship between Review and Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);  // Each review belongs to a patient
    }
}
