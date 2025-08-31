<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    // Register method
    function register(Request $req)
    {
        // Check if the email exists in the main User table
        if (User::where('Email', $req->email)->exists()) {
            return response()->json(['error' => 'Email already exists']);
        }

        // Create the user in the main User table
        $user = new User;
        $user->FullName = $req->fullname;
        $user->Email = $req->email;
        $user->Role = $req->role;
        $user->Password = Hash::make($req->input('password'));
        $user->save();

        // Create a dynamic table based on the user's role
        $this->createRoleTable($req->role);

        return $user;
    }

    // Function to create a dynamic table based on the role (doctor or patient)
    private function createRoleTable($role)
    {
        $tableName = strtolower($role); // Convert the role to lowercase (doctor -> doctor, patient -> patient)

        // Check if the table for the given role already exists
        if (!Schema::hasTable($tableName)) {
            // Create the table if it doesn't exist
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();  // Auto-incrementing primary key
                $table->string('fullname');
                $table->string('email')->unique();
                $table->string('password');
                $table->timestamps();
            });
        }
    }

    // Login method
    function login(Request $req)
    {
        $user = User::where('Email', $req->email)->first();

        if (!$user || !Hash::check($req->password, $user->Password)) {
            return response()->json(['error' => 'Email or password is incorrect'], Response::HTTP_UNAUTHORIZED);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Login successful',
            'user'    => $user,
            'token'   => $token  // Send token to frontend
        ], Response::HTTP_OK);
    }

    // Check if email already exists
    function checkEmail(Request $req)
    {
        $email = $req->input('email');

        // Check if the email exists in the database (from the User table)
        $user = User::where('Email', $email)->first();

        // If the email exists, return true; otherwise, return false
        if ($user) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }
}
