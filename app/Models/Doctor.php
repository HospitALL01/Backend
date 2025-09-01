<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <= important
use Tymon\JWTAuth\Contracts\JWTSubject;                 // <= important

class Doctor extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'doctors';
    protected $primaryKey = 'id';

    protected $fillable = [
        'd_name',
        'd_email',
        'd_phone',
        'd_password',
    ];

    protected $hidden = [
        'd_password',
    ];

    public $timestamps = true;

    // Tell Laravel which column is the password
    public function getAuthPassword()
    {
        return $this->d_password;
    }

    // ========= JWTSubject methods =========
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'doctor'];
    }

    // If you want "email" to be the username field:
    public function getAuthIdentifierName()
    {
        return 'd_email';
    }
}
