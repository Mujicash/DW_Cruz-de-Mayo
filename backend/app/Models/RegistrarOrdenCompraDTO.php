<?php

namespace App\Models;

final class RegistrarOrdenCompraDTO {

    private int $idUsuario;
    private int $idSucursal;
    private int $idProveedor;

    public function __construct(int $idUsuario, int $idSucursal, int $idProveedor) {
        $this->idUsuario   = $idUsuario;
        $this->idSucursal  = $idSucursal;
        $this->idProveedor = $idProveedor;
    }

    /**
     * @return int
     */
    public function getIdUsuario(): int {
        return $this->idUsuario;
    }

    /**
     * @return int
     */
    public function getIdSucursal(): int {
        return $this->idSucursal;
    }

    /**
     * @return int
     */
    public function getIdProveedor(): int {
        return $this->idProveedor;
    }
}
