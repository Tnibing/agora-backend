<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreUserRequest;
use App\Http\Requests\Api\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\UserDeleteRequest;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller
{
    public function __construct(private UserServiceInterface $userService) {}

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->userService->updateAuthenticatedUser(
            $request->user()->id,
            $data
        );

        return response()->json([
            'message' => 'User updated successfully',
            'user' => new UserResource($user),
        ]);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $user = $this->userService->create($request->validated());

        return response()->json([
            'message' => 'User created successfully',
            'user' => new UserResource($user),
            'token' => $user->createToken('api-token')->plainTextToken
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $id = (int) $id;
        $user = $this->userService->find($id);

        return response()->json([
            'user' => new UserResource($user)
        ]);
    }

    public function hasDeleteRequest(Request $request): JsonResponse
    {
        $exists = $this->userService->userHasDeleteRequest(
            $request->user()->id
        );

        return response()->json([
            'hasDeleteRequest' => $exists
        ]);
    }

    public function deleteRequest(Request $request): JsonResponse
    {
        UserDeleteRequest::firstOrCreate([
            'user_id' => $request->user()->id
        ]);

        return response()->json([
            'message' => 'Delete request created'
        ]);
    }

    public function cancelDeleteRequest(Request $request): JsonResponse
    {
        $deleted = UserDeleteRequest::where('user_id', $request->user()->id)->delete();

        if (!$deleted) {
            return response()->json([
                'message' => 'No existe solicitud para cancelar'
            ], 404);
        }

        return response()->json([
            'message' => 'Solicitud de eliminación cancelada'
        ]);
    }
}
