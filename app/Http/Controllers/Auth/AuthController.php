<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{

    public function register(Request $req)
    {
        $req->validate([
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|confirmed'
        ]);
        $user = new User();
        $user->firstname = $req->firstname;
        $user->lastname = $req->lastname;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->save();
        $token = $user->createToken($req->email, ['*'], now()->addWeek());
        $user->assignRole('free_user');

        return [
            'message' => 'User created successfully',
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function login(Request $req)
    {
        $req->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);
        $user = User::where('email', $req->email)->first();
        if (!$user || !Hash::check($req->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $token = $user->createToken($req->email, ['*'], now()->addWeek(1));

        return [
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

}
