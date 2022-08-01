<?php

namespace App\Models;

interface SucursalRepository {

    public function create(Sucursal $sucursal): bool;
    public function getById(int $idSucursal): ?Sucursal;
    public function getIdByName(string $nombre): int;
    public function getAll(): array;
    public function update(Sucursal $sucursal): int;
    public function delete(int $idSucursal): int;

}
