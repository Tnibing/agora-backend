<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Resources\UserResource;
use App\Services\Contracts\AuthServiceInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthController extends Controller
{
    public function __construct(private AuthServiceInterface $authService) {}

    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $tokenData = $this->authService->login($data['email'], $data['password']);

        return response()->json([
            'message' => 'Login successful',
            'user' => new UserResource($tokenData['user']),
            'token' => $tokenData['token'],
        ], 200);
    }
}
