<?php

namespace App\Models;

interface OrdenCompraRepository {

    public function create(OrdenCompra $ordenCompra): int;

    public function createDetail(DetalleCompra $detalleCompra): bool;

    public function getAll(): array;

    public function getDetail(int $idOrden): DetalleCompraDTO;

    public function getProductsFromOrder(int $idOrden): array;

    public function getBranch(int $idCompra);

    public function increaseStock(int $sucursal, int $id, int $cantidad);

    public function getDate(int $idCompra);

    public function createGuide(string $numGuia, string $motivo, string $fechaInicio, string $fechaRec, string $imagen, int $idCompra);
}
