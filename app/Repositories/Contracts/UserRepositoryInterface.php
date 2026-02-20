<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByEmail(string $email): ?User;

    public function updateById(int $id, array $data): User;

    public function create(array $data): User;

    public function find(int $id): User;

    public function userHasDeleteRequest(int $userId): bool;
}
