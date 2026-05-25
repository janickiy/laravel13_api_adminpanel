<?php

namespace App\DTO\Catalog;

use App\DTO\Contracts\UpdatableData;

final readonly class CatalogData implements UpdatableData
{
    public function __construct(
        public int $id,
        public string $name,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: (string) $data['name'],
        );
    }

    public function id(): int
    {
        return $this->id;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
