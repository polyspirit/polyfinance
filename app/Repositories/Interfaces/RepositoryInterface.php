<?php

namespace App\Repositories\Interfaces;

interface RepositoryInterface
{
    public function all(array $columns = ['*']): \Illuminate\Database\Eloquent\Collection;

    public function paginate(int $perPage = 20, array $columns = ['*']): \Illuminate\Database\Eloquent\Collection;

    public function find(int $id, array $columns = ['*']): \Illuminate\Database\Eloquent\Model;

    public function findBy(string $field, mixed $value, array $columns = ['*']): \Illuminate\Database\Eloquent\Model;

    public function getWhere(string $field, mixed $value, array $columns = ['*']): \Illuminate\Database\Eloquent\Collection;

    public function create(array $data): \Illuminate\Database\Eloquent\Model;

    public function update(array $data, int $id): bool;

    public function delete(int $id): bool|null;
}
