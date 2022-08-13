<?php

namespace App\Models\Entidades;

class OrdenSalida {

    private int    $id;
    private string $fecha;
    private int    $idUsuario;
    private int    $idSucursal;

    /**
     * @param int $id
     * @param string $fecha
     * @param int $idUsuario
     * @param int $idSucursal
     */
    public function __construct(int $idUsuario, int $idSucursal, int $id = 0, string $fecha = '') {
        $this->id         = $id;
        $this->fecha      = $fecha;
        $this->idUsuario  = $idUsuario;
        $this->idSucursal = $idSucursal;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFecha(): string {
        return $this->fecha;
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

}
