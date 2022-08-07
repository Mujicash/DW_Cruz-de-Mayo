<?php

namespace App\Models;

interface OrdenCompraRepository {

    public function create(RegistrarOrdenCompraDTO $ordenCompraDTO): int;

    public function createDetail(RegistrarDetalleCompraDTO $detalleCompraDTO): bool;
}
