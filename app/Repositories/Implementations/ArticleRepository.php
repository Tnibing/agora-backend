<?php

namespace App\Repositories\Implementations;

use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;

class ArticleRepository implements ArticleRepositoryInterface
{
    public function getMainArticles()
    {
        return Article::with(['user', 'tags'])
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get()
        ;
    }

    public function find(int $id)
    {
        return Article::with(['user', 'tags', 'comments.author', 'comments.replies.author'])
            ->findOrFail($id)
        ;
    }

    public function indexByCategory(string $category)
    {
        return Article::with(['user', 'tags', 'comments'])
            ->whereHas('tags', function ($query) use ($category) {
                $query->where('name', 'LIKE', $category);
        })
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
