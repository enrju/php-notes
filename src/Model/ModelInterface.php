<?php

declare(strict_types=1);

namespace App\Model;

interface ModelInterface
{
    public function get(int $id): array;

    public function list(): array;

    public function create(array $data): void;

    public function edit(int $id, array $data): void;

    public function delete(int $id): void;
}
