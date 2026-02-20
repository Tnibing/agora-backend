<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Services\Contracts\TagServiceInterface;
use Illuminate\Http\JsonResponse;

class TagController extends Controller
{
    public function __construct(
        private TagServiceInterface $tagService
    ) {}

    public function index(): JsonResponse
    {
        $tags = $this->tagService->index();

        return response()->json([
            'tags' => TagResource::collection($tags)
        ]);
    }
}
