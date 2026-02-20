<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Services\Contracts\ArticleServiceInterface;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(
        private ArticleServiceInterface $articleService
    ) {}

    public function main(): JsonResponse
    {
        $mainArticles = $this->articleService->mainArticles();

        return response()->json([
            'main_articles' => ArticleResource::collection($mainArticles)
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $article = $this->articleService->find($id);

        return response()->json([
            'article' => new ArticleResource($article)
        ]);
    }

    public function indexByCategory(string $category): JsonResponse
    {
        $articles = $this->articleService->indexByCategory($category);

        return response()->json([
            'articles' => ArticleResource::collection($articles)
        ]);
    }
}
