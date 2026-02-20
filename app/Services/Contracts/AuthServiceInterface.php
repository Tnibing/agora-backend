<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function login(string $email, string $password): array;
}
