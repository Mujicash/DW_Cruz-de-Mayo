<?php

namespace App\Models;

interface OrdenCompraRepository {

    public function create(OrdenCompra $ordenCompra): int;

    public function createDetail(DetalleCompra $detalleCompra): bool;

    public function getAll(): array;

    public function getDetail(int $idOrden): DetalleCompraDTO;
}
