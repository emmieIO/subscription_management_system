<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

// create an interface
interface UserServiceInterface
{
    public function register(array $data) : array;
    public function login(array $data) : User;
}
class UserService implements UserServiceInterface {
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function register(array $data) :array{
        // register logic here
        return DB::transaction(function() use ($data) {
            $user = $this->userRepository->create($data);
            $user->assignRole('free_user');
            $token = $this->userRepository->createToken($user);
            return compact('user', 'token');
        });
    }

    public function login(array $data):User{
        // login logic here
        $user = $this->userRepository->findByEmail($data['email']);
        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new \Exception('Invalid credentials');
        }
        return $user;
    }
}