<?php

namespace App\Repositories\Implementations;

use App\Models\User;
use App\Models\UserDeleteRequest;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function updateById(int $id, array $data): User
    {
        $user = User::find($id);

        if (!$user) {
            throw new ModelNotFoundException('User not found');
        }

        $user->update($data);

        return $user;
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function find(int $id): User
    {
        return User::findOrFail($id);
    }

    public function userHasDeleteRequest(int $userId): bool
    {
        return UserDeleteRequest::where('user_id', $userId)->exists();
    }
}
