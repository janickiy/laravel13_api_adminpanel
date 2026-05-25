<?php

namespace App\Repositories;

use App\DTO\Contracts\ModelData;
use App\DTO\Contracts\UpdatableData;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function all(): Collection;

    public function find(int $id): ?Model;

    public function create(ModelData $data): mixed;

    public function update(UpdatableData $data): ?Model;

    public function delete(int $id): bool;
}
