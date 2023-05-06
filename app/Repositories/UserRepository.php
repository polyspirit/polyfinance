<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\User;

class UserRepository extends MainRepository
{
    protected string $model = User::class;
}
