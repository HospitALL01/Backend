<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <= important
use Tymon\JWTAuth\Contracts\JWTSubject;                 // <= important

class DoctorInfo extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'doctor_info';  // Table name
    protected $primaryKey = 'id';      // Primary key

    protected $fillable = [
        'doctor_name', 
        'gender', 
        'nationality', 
        'specialization', 
        'license_number', 
        'license_issue_date', 
        'hospital_name', 
        'years_of_experience', 
        'phone', 
        'email', 
        'current_position', 
        'previous_positions'
    ];

    protected $hidden = [
        'phone',   // Hidden fields, if any, can be added here
    ];

    public $timestamps = true;  // Automatically manage created_at and updated_at

    // Method to get the authentication password (not applicable in this case)
    public function getAuthPassword()
    {
        return $this->password;  // assuming you will handle passwords in the future
    }

    // ========= JWTSubject methods =========
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Return the primary key of the model
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'doctor'];  // Role for the JWT token
    }

    public function getAuthIdentifierName()
    {
        return 'email';  // Use email as the identifier for login
    }
}
