<?php

namespace App\Repositories;

use App\DTO\Contracts\UpdatableData;
use App\Models\Catalog;

class CatalogRepository extends BaseRepository
{
    public function __construct(Catalog $model)
    {
        parent::__construct($model);
    }

    /**
     * @param UpdatableData $data
     * @return Catalog|null
     */
    public function update(UpdatableData $data): ?Catalog
    {
        $model = $this->model->find($data->id());

        if ($model) {
            $model->fill($data->toArray());
            $model->save();

            return $model;
        }
        return null;
    }
}
