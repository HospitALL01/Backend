<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorInfo;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'doctor_id',
        'doctor_name',
        'patient_id',
        'patient_name',
        'appointment_date',
        'payment_method',
        'fees',
        'status',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'fees' => 'decimal:2',
    ];
}
