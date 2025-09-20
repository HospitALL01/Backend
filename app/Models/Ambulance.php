<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // Correctly importing Model class

class Ambulance extends Model // Ambulance model extends the Eloquent Model class
{
    use HasFactory;

    // Define the table name if it’s different from the default plural form
    protected $table = 'ambulances';

    // Primary key
    protected $primaryKey = 'id';

    // Mass assignable fields
    protected $fillable = [
        'hospital_name',
        'latitude',
        'longitude',
        'driver_name',
        'driver_phone',
        'status',
    ];

    // Timestamps (created_at, updated_at)
    public $timestamps = true;
}
