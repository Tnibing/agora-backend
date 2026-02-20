<?php

namespace App\Repositories\Implementations;

use App\Models\Tag;
use App\Repositories\Contracts\TagRepositoryInterface;

class TagRepository implements TagRepositoryInterface
{
    public function index()
    {
        return Tag::all();
    }
}
