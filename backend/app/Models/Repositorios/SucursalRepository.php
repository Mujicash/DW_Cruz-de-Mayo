<?php

namespace App\Models\Repositorios;

use App\Models\Entidades\Sucursal;

interface SucursalRepository {

    public function create(Sucursal $sucursal): bool;

    public function getById(int $idSucursal): ?Sucursal;

    public function getIdByName(string $nombre): int;

    public function getAll(): array;

    public function update(Sucursal $sucursal): int;

    public function delete(int $idSucursal): int;

}
