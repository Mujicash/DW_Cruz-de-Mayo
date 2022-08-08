<?php

namespace App\Models;

interface OrdenSalidaRepository {

    public function create(OrdenSalida $ordenSalida);

    public function createDetail(DetalleSalida $detalleSalida);

    public function getAll();

    public function getDetail(int $id);
}
