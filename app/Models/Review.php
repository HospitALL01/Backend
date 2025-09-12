<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['doctor_id', 'patient_id', 'rating', 'comment', 'patient_name'];

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
