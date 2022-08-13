<?php

namespace App\Models\Repositorios;

interface StockRepository {

    public function getAll(int $idSucursal);

}
