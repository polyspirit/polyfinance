<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\Tag;

class TagRepository extends MainRepository
{
    protected string $model = Tag::class;
}
