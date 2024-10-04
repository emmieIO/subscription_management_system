<?php

namespace App\Repositories;

use App\Models\User;

interface UserRepositoryInterface
{
    public function create(array $data): User;
    public function update(array $data, int $id): User;
    public function delete(int $id): bool;
    public function find(int $id): User;
    public function findByEmail(string $email): User;
    public function all(): array;
    public function createToken(): string;
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
        return $user->update($data);
    }

    public function delete(int $id): bool
    {
        return User::destroy($id);
    }

    public function find(int $id): User
    {
        return User::find($id);
    }

    public function findByEmail(string $email): User
    {
        return User::where('email', $email)->first();
    }

    public function all(): array
    {
        return User::all()->toArray();
    }

    public function createToken(User $user=null): string{
        return $user->createToken($user->email, ['*'], now()
        ->addWeek())
        ->plainTextToken;
    }
}