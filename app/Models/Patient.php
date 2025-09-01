<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // <= important
use Tymon\JWTAuth\Contracts\JWTSubject;                 // <= important

class Patient extends Authenticatable implements JWTSubject
{
    use HasFactory;

    protected $table = 'patients';
    protected $primaryKey = 'id';

    protected $fillable = [
        'p_name',
        'p_email',
        'p_phone',
        'p_password',
    ];

    protected $hidden = [
        'p_password',
    ];

    public $timestamps = true;

    public function getAuthPassword()
    {
        return $this->p_password;
    }

    // ========= JWTSubject methods =========
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return ['role' => 'patient'];
    }

    public function getAuthIdentifierName()
    {
        return 'p_email';
    }
}
