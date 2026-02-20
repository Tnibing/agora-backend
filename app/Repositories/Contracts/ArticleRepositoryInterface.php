<?php

namespace App\Repositories\Contracts;

interface ArticleRepositoryInterface
{
    public function getMainArticles();

    public function find(int $id);

    public function indexByCategory(string $category);
}
