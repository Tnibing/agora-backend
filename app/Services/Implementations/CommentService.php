<?php

namespace App\Services\Implementations;

use App\Models\Comment;
use App\Services\Contracts\CommentServiceInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CommentService implements CommentServiceInterface
{
    public function __construct(private CommentRepositoryInterface $commentRepository) {}

    public function createComment(array $data): Comment
    {
        return $this->commentRepository->create($data);
    }

    public function updateComment(int $commentId, int $userId, string $content): Comment
    {
        return $this->commentRepository->updateContent($commentId, $userId, $content);
    }
}
