<?php

namespace App\Services\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function updateAuthenticatedUser(int $userId, array $data): User;

    public function create(array $data): User;

    public function find(int $id): User;

    public function userHasDeleteRequest(int $userId): bool;
}
