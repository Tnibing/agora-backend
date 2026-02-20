<?php

namespace App\Services\Implementations;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    public function __construct(private UserRepositoryInterface $userRepository) {}

    public function updateAuthenticatedUser(int $userId, array $data): User
    {
        if (isset($data['user_image_url']) && $data['user_image_url'] instanceof UploadedFile) {

            $user = $this->userRepository->find($userId);

            if ($user->user_image_url) {
                Storage::disk('public')->delete($user->user_image_url);
            }

            $data['user_image_url'] = $data['user_image_url']->store('users', 'public');
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->updateById($userId, $data);
    }

    public function create(array $data): User
    {
        if (isset($data['user_image'])) {
            $data['user_image_url'] = $data['user_image']
                ->store('images/user', 'public');

            unset($data['user_image']);
        }

        return $this->userRepository->create($data);
    }

    public function find(int $id): User
    {
        return $this->userRepository->find($id);
    }

    public function userHasDeleteRequest(int $userId): bool
    {
        return $this->userRepository->userHasDeleteRequest($userId);
    }
}
