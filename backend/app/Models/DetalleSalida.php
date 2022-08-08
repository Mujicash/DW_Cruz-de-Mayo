<?php

namespace App\Models;

class DetalleSalida {

    private int $id;
    private int $idSalida;
    private int $idProducto;
    private int $cantidad;

    /**
     * @param int $id
     * @param int $idSalida
     * @param int $idProducto
     * @param int $cantidad
     */
    public function __construct(int $idSalida, int $idProducto, int $cantidad, int $id = 0) {
        $this->id         = $id;
        $this->idSalida   = $idSalida;
        $this->idProducto = $idProducto;
        $this->cantidad   = $cantidad;
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
    public function getIdSalida(): int {
        return $this->idSalida;
    }

    /**
     * @return int
     */
    public function getIdProducto(): int {
        return $this->idProducto;
    }

    /**
     * @return int
     */
    public function getCantidad(): int {
        return $this->cantidad;
    }

}
