<?php

namespace App\Repositories;

use App\Repositories\MainRepository;
use App\Models\Flow;

class FlowRepository extends MainRepository
{
    protected string $model = Flow::class;
}
