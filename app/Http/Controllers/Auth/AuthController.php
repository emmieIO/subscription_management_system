<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Services\UserService;


class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    public function register(RegisterRequest $req)
    {
        try {
            $data = $req->validated();
            $user = $this->userService->register($data);
            return response()->response_success("Registration successful", $user, 201);
        } catch (\Throwable $th) {
            return response()->response_error($th->getMessage(), 500);
        }
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
        $token = $user->createToken($req->email, ['*'], now()->addWeek());

        return response()->json([
            'message' => 'User logged in successfully',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);
    }

}
