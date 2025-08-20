<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class useController extends Controller
{
    function register(Request $req){
        if(User::where('Email', $req->email)->exists()) {
            return response()->json(['error' => 'Email already exists']);
        }
        $user = new User;
        $user->FullName = $req->fullname;
        $user->Email = $req->email;
        $user->Role= $req->role;
        $user->Password = Hash::make($req->input('password'));
        $user->save();
        return $user;
    }

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
            'token'   => $token   // <-- Send token to frontend
        ], Response::HTTP_OK);
    }
}
