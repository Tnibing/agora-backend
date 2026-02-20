<?php

namespace App\Services\Contracts;

interface ArticleServiceInterface
{
    public function mainArticles();

    public function find(int $id);

    public function indexByCategory(string $category);
}
