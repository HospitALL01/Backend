<?php

return [

    'defaults' => [
        'guard' => 'api',      // you can keep 'api' if you already use it
        'passwords' => 'users',
    ],
    'guards' => [
        'api' => [
            'driver' => 'jwt',
            'provider' => 'users', // can be kept if you still have default users
        ],

        // Optional: separate guards (useful for role-based middleware later)
        'doctor' => [
            'driver'   => 'jwt',
            'provider' => 'doctors',
        ],
        'patient' => [
            'driver'   => 'jwt',
            'provider' => 'patients',
        ],
    ],

    'providers' => [
        // keep this if you still have default users somewhere
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // NEW: doctor provider
        'doctors' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Doctor::class,
        ],

        // NEW: patient provider
        'patients' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Patient::class,
        ],
    ],
];
