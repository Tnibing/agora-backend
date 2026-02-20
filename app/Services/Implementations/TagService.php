<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\TagRepositoryInterface;
use App\Services\Contracts\TagServiceInterface;

class TagService implements TagServiceInterface
{
    public function __construct(
        private TagRepositoryInterface $tagRepository
    ) {}

    public function index()
    {
        return $this->tagRepository->index();
    }
}
