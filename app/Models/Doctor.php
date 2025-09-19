<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // login এর জন্য
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Support\Facades\Hash;

class Doctor extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'd_name',
        'd_email',
        'd_phone',
        'd_password', // আমরা টেবিলে d_password ধরছি
    ];

    protected $hidden = [
        'd_password',
        'remember_token',
    ];

    public $timestamps = true;

    // Laravel কে বলি "এই" কলামটা password
    public function getAuthPassword()
    {
        return $this->d_password;
    }

    // login username field
    public function getAuthIdentifierName()
    {
        return 'd_email';
    }

    // JWT
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'doctor'];
    }

    // d_password সবসময় hash করে সেভ করবো (controller এ আলাদা Hash::make লাগবে না)
    public function setDPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['d_password'] = Hash::make($value);
        }
    }

    // (optional) relation to reviews যদি থাকে
    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class, 'doctor_id', 'id');
    }
}
