<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCommentRequest;
use App\Http\Requests\Api\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Services\Contracts\CommentServiceInterface;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentController extends Controller
{
    public function __construct(
        private CommentServiceInterface $commentService
    ) {}

    public function store(StoreCommentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $comment = $this->commentService->createComment($data);

        return response()->json([
            'message' => 'Comment created successfully',
            'comment' => new CommentResource($comment->load('author', 'replies')),
        ], 201);
    }

    public function update(UpdateCommentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $comment = $this->commentService->updateComment(
            $data['comment_id'],
            Auth::id(),
            $data['content']
        );

        return response()->json([
            'message' => 'Comment updated successfully',
            'comment' => new CommentResource($comment->load('author', 'replies')),
        ], 200);
    }
}
