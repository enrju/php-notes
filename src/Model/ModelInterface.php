<?php

declare(strict_types=1);

namespace App\Model;

interface ModelInterface
{
    public function get(int $id): array;

    public function list(
        string $sortBy,
        string $sortOrder,
        int $pageNumber,
        int $pageSize
    ): array;

    public function create(array $data): int;

    public function edit(int $id, array $data): void;

    public function delete(int $id): void;

    public function count(): int;
}
