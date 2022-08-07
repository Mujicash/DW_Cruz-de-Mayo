<?php

namespace App\Models;

class DetalleCompra {

    private int   $id;
    private int   $idCompra;
    private int   $idProducto;
    private float $precio;
    private int   $cantidad;

    /**
     * @param int $idCompra
     * @param int $idProducto
     * @param float $precio
     * @param int $cantidad
     * @param int $id
     */
    public function __construct(int $idCompra, int $idProducto, float $precio, int $cantidad, int $id = 0) {
        $this->idCompra   = $idCompra;
        $this->idProducto = $idProducto;
        $this->precio     = $precio;
        $this->cantidad   = $cantidad;
        $this->id         = $id;
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
