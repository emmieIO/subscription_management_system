<?php

namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Http\Requests\Authentication\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\Authentication\RegisterRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    protected UserService $userService;
    public function __construct(UserService $userService){
        $this->userService = $userService;
    }

    /**
     * @param RegisterRequest $registerRequest
     * @return object
     */
    public function register(RegisterRequest $registerRequest): object
    {
        try {
            $user = $this->userService->register($registerRequest->toArray());
            return response()->response_success("Registration successful", $user, 201);
        } catch (\Throwable $th) {
            return response()->response_error($th->getMessage(), 500);
        }
    }

    public function login(LoginRequest $req)
    {
        try {
            $data = $req->validated();
            $user = $this->userService->authenticate($data);
            return response()->response_success("Login in success",$user, 200);
        } catch (\Throwable $th) {
            return response()->response_error($th->getMessage(), 401);
        }
    }
}
