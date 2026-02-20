<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Services\Contracts\ArticleServiceInterface;

class ArticleService implements ArticleServiceInterface
{
    public function __construct(
        private ArticleRepositoryInterface $articleRepo
    ) {}

    public function mainArticles()
    {
        return $this->articleRepo->getMainArticles();
    }

    public function find(int $id)
    {
        return $this->articleRepo->find($id);
    }

    public function indexByCategory(string $category)
    {
        return $this->articleRepo->indexByCategory($category);
    }
}
