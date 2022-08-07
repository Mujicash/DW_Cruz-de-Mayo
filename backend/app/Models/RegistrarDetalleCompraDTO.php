<?php

namespace App\Models;

class RegistrarDetalleCompraDTO {

    private int   $idCompra;
    private int   $idProducto;
    private float $precio;
    private int   $cantidad;

    /**
     * @param int $idCompra
     * @param int $idProducto
     * @param float $precio
     * @param int $cantidad
     */
    public function __construct(int $idCompra, int $idProducto, float $precio, int $cantidad) {
        $this->idCompra   = $idCompra;
        $this->idProducto = $idProducto;
        $this->precio     = $precio;
        $this->cantidad   = $cantidad;
    }

    /**
     * @return int
     */
    public function getIdCompra(): int {
        return $this->idCompra;
    }

    /**
     * @return int
     */
    public function getIdProducto(): int {
        return $this->idProducto;
    }

    /**
     * @return float
     */
    public function getPrecio(): float {
        return $this->precio;
    }

    /**
     * @return int
     */
    public function getCantidad(): int {
        return $this->cantidad;
    }
}
