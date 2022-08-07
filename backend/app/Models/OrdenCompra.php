<?php

namespace App\Models;

use DateTime;

class OrdenCompra {

    private int    $idUsuario;
    private int    $idSucursal;
    private int    $idProveedor;
    private int    $id;
    private int    $estado;
    private string $fechaCompra;

    public function __construct(int $idUsuario, int $idSucursal, int $idProveedor, int $id = 0, int $estado = 0, string $fechaCompra = '') {
        $this->idUsuario   = $idUsuario;
        $this->idSucursal  = $idSucursal;
        $this->idProveedor = $idProveedor;
        $this->id          = $id;
        $this->estado      = $estado;
        $this->fechaCompra = $fechaCompra;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getEstado(): int {
        return $this->estado;
    }

    /**
     * @return string
     */
    public function getFechaCompra(): string {
        return $this->fechaCompra;
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
