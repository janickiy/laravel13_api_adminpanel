<?php

namespace App\DTO\Contracts;

interface UpdatableData extends ModelData
{
    public function id(): int;
}
