<?php

namespace App\Repositories;


use App\DTO\Contracts\ModelData;
use App\DTO\Contracts\UpdatableData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{

    /**
     * @param Model $model
     */
    public function __construct(protected Model $model)
    {
    }

    /**
     * @param ModelData $data
     * @return mixed
     */
    public function create(ModelData $data): mixed
    {
        return $this->model->create($data->toArray());
    }

    /**
     * @param UpdatableData $data
     * @return Model|null
     */
    public function update(UpdatableData $data): ?Model
    {
        $model = $this->model->find($data->id());

        if ($model) {
            $model->fill($data->toArray());
            $model->save();

            return $model;
        }
        return null;
    }

    /**
     * @return Collection
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function find(int $id): ?Model
    {
        return $this->model->find($id);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $model = $this->model->find($id);
        if ($model) {
            $model->delete();
            return true;
        }
        return false;
    }
}
