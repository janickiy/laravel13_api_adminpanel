<?php

namespace App\Repositories;

use App\DTO\Contracts\UpdatableData;
use App\Models\Notes;

class NoteRepository extends BaseRepository
{
    public function __construct(Notes $model)
    {
        parent::__construct($model);
    }

    /**
     * @param UpdatableData $data
     * @return Notes|null
     */
    public function update(UpdatableData $data): ?Notes
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
