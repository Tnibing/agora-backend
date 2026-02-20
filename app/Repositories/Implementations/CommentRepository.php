<?php

namespace App\Repositories\Implementations;

use App\Models\Comment;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Repositories\Contracts\CommentRepositoryInterface;

class CommentRepository implements CommentRepositoryInterface
{
    public function create(array $data): Comment
    {
        return Comment::create($data);
    }

    public function updateContent(int $commentId, int $userId, string $content): Comment
    {
        $comment = Comment::where('id', $commentId)
            ->where('user_id', $userId)
            ->first();

        if (!$comment) {
            throw new ModelNotFoundException('Comment not found or not authorized');
        }

        $comment->content = $content;
        $comment->save();

        return $comment;
    }
}
