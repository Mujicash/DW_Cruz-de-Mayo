<?php

namespace App\Models;

interface StockRepository {

    public function getAll(int $idSucursal);

}
