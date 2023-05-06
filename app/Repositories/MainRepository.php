<?php

namespace App\Repositories;

use App\Repositories\Interfaces\RepositoryInterface;

class MainRepository implements RepositoryInterface
{
    protected string $model;

    public final function __construct()
    {
        if (!isset($this->model)) {
            throw new \LogicException(get_class($this) . ' must have a $model');
        }
    }

    public function all(array $columns = ['*']): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model::all($columns);
    }

    public function paginate(int $perPage = 20, array $columns = ['*']): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model::limit($perPage)->get($columns);
    }

    public function find(int $id, array $columns = ['*']): \Illuminate\Database\Eloquent\Model
    {
        return $this->model::find($id, $columns)->first();
    }

    public function findBy(string $field, mixed $value, array $columns = ['*']): \Illuminate\Database\Eloquent\Model
    {
        return $this->model::select($columns)->where($field, $value)->first();
    }

    public function findOrCreate(string $field, mixed $value, array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->model::firstOrCreate(
            [$field => $value],
            $data
        );
    }

    public function getWhere(string $field, mixed $value, array $columns = ['*']): \Illuminate\Database\Eloquent\Collection
    {
        return $this->model::select($columns)->where($field, $value)->get();
    }

    public function create(array $data): \Illuminate\Database\Eloquent\Model
    {
        return $this->model::create($data);
    }

    public function update(array $data, $id): bool
    {
        return $this->model::find($id)->update($data);
    }

    public function delete(int $id): bool|null
    {
        return $this->model::destroy($id);
    }
}
