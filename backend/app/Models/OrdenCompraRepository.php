<?php

namespace App\Models;

interface OrdenCompraRepository {

    public function create(OrdenCompra $ordenCompraDTO): int;

    public function createDetail(DetalleCompra $detalleCompraDTO): bool;

    public function getAll(): array;
}
