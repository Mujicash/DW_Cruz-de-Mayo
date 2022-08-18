<?php

namespace App\Models\Repositorios;

use App\Models\Entidades\DetalleSalida;
use App\Models\Entidades\OrdenSalida;

interface OrdenSalidaRepository {

    public function create(OrdenSalida $ordenSalida);

    public function createDetail(DetalleSalida $detalleSalida);

    public function getAllBranchOrders(int $idSucursal);

    public function getDetail(int $id);
}
