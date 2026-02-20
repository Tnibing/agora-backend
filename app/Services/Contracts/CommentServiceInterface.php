<?php

namespace App\Services\Contracts;

use App\Models\Comment;

interface CommentServiceInterface
{
    public function createComment(array $data): Comment;

    public function updateComment(int $commentId, int $userId, string $content): Comment;
}
