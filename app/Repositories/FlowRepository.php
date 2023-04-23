<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FlowRepositoryInterface;
use App\Models\Flow;

class FlowRepository implements FlowRepositoryInterface
{
    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return Flow::all();
    }

    public function getById(int $modelId)
    {

    }

    public function create(array $details)
    {
        Flow::create($details);
    }
    public function updateOrder(int $modelId, array $details)
    {

    }
    public function delete(int $modelId)
    {

    }
}
