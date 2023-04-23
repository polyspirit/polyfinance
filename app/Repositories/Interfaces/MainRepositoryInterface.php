<?php

namespace App\Repositories\Interfaces;

interface MainRepositoryInterface
{
    public function getAll();
    public function getById(int $modelId);
    public function create(array $details);
    public function updateOrder(int $modelId, array $details);
    public function delete(int $modelId);
}
