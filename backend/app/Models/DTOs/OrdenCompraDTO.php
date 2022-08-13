<?php

namespace App\Models\DTOs;

use JsonSerializable;

class OrdenCompraDTO implements JsonSerializable {

    private int    $idOrden;
    private string $fechaCompra;
    private string $rucProveedor;
    private float  $costoTotal;
    private string $estado;

    /**
     * @param int $idOrden
     * @param string $fechaCompra
     * @param string $rucProveedor
     * @param float $costoTotal
     * @param string $estado
     */
    public function __construct(int $idOrden, string $fechaCompra, string $rucProveedor, float $costoTotal, string $estado) {
        $this->idOrden      = $idOrden;
        $this->fechaCompra  = $fechaCompra;
        $this->rucProveedor = $rucProveedor;
        $this->costoTotal   = $costoTotal;
        $this->estado       = $estado;
    }

    /**
     * @return int
     */
    public function getIdOrden(): int {
        return $this->idOrden;
    }

    /**
     * @return string
     */
    public function getFechaCompra(): string {
        return $this->fechaCompra;
    }

    /**
     * @return string
     */
    public function getRucProveedor(): string {
        return $this->rucProveedor;
    }

    /**
     * @return float
     */
    public function getCostoTotal(): float {
        return $this->costoTotal;
    }

    /**
     * @return string
     */
    public function getEstado(): string {
        return $this->estado;
    }

    public function jsonSerialize(): array {
        return [
            'idOrden'      => $this->idOrden,
            'fechaCompra'  => $this->fechaCompra,
            'rucProveedor' => $this->rucProveedor,
            'costoTotal'   => $this->costoTotal,
            'estado'       => $this->estado
        ];
    }
}
