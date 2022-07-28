<?php

namespace App\Models;

interface ProductoRepository {

    public function create(Producto $producto): bool;

    public function getByName(string $nombre): ?Producto;

    public function getById(int $id): ?Producto;

    public function getAll(): array;

    public function update(Producto $producto): bool;

    public function delete(int $id): bool;
}
