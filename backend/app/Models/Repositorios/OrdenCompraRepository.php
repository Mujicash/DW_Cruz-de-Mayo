<?php

namespace App\Models\Repositorios;

use App\Models\DTOs\DetalleCompraDTO;
use App\Models\Entidades\DetalleCompra;
use App\Models\Entidades\OrdenCompra;

interface OrdenCompraRepository {

    public function create(OrdenCompra $ordenCompra): int;

    public function createDetail(DetalleCompra $detalleCompra): bool;

    public function getAll(int $idUsuario): array;

    public function getDetail(int $idOrden): DetalleCompraDTO;

    public function getProductsFromOrder(int $idOrden): array;

    public function getBranch(int $idCompra);

    public function increaseStock(int $sucursal, int $id, int $cantidad);

    public function getDate(int $idCompra);

    public function createGuide(string $numGuia, string $motivo, string $fechaInicio, string $fechaRec, string $imagen, int $idCompra);
}
