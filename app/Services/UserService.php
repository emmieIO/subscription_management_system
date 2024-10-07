<?php

namespace App\Services;

use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\DB;


// create an interface
interface UserServiceInterface
{
    public function register(array $data) : UserResource;
    public function authenticate(array $data): UserResource ;
}
class UserService implements UserServiceInterface {
    protected UserRepository $userRepository;

    public function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function register(array $data) : UserResource{
        // register logic here
        return DB::transaction(function() use ($data) {
            $user = $this->userRepository->create($data);
            $user->assignRole('free_user');
            $token = $this->userRepository->createToken($user);
            $user->token = $token;
            return new UserResource($user);
        });
    }

    /**
     * @throws Exception
     */
    public function authenticate(array $data): UserResource{
        // login logic here
        $user = $this->userRepository->findByEmail($data['email']);
        $checkAuth = $this->userRepository->checkCredentials($user, $data['password']);
        if(!$checkAuth){
            throw new Exception('Invalid credentials');
        }
        $token = $this->userRepository->createToken($user);
        $user->token = $token;
        return new UserResource($user);
    }
}
