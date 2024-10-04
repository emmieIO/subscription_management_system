<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function update(array $data, int $id): User;
    public function delete(int $id): bool;
    public function find(int $id): User;
    public function findByEmail(string $email): ?User;
    public function all(): array;
    public function createToken(User $user): string;
    public function checkCredentials(?object $user, string $password): bool;
}

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(array $data, int $id): User
    {
        $user = User::find($id);
        $user->update($data);
        return $user;
    }

    public function delete(int $id): bool
    {
        return User::destroy($id);
    }

    public function find(int $id): User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): ?User
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

    public function all(): array
    {
        return User::all()->toArray();
    }

    public function createToken(User $user): string{
        return $user->createToken($user->email, ['*'], now()
        ->addWeek())
        ->plainTextToken;
    }

    public function checkCredentials(?object $user, string $password): bool
    {
        return $user && Hash::check($password, $user['password']);
    }
}