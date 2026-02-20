<?php

namespace App\Repositories\Contracts;

use App\Models\Comment;

interface CommentRepositoryInterface
{
    public function create(array $data): Comment;

    public function updateContent(int $commentId, int $userId, string $content): Comment;
}
