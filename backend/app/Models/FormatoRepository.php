<?php

namespace App\Models;

interface FormatoRepository {

    public function create(Formato $formato): bool;

    public function getById(int $id): ?Formato;

    public function getByName(string $name): ?Formato;

    public function getAll(): array;
}
