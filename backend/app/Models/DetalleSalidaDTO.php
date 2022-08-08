<?php

namespace App\Models;

use JsonSerializable;

class DetalleSalidaDTO implements JsonSerializable {

    private OrdenSalidaDTO $orden;
    private array          $productos;

    /**
     * @param OrdenSalidaDTO $orden
     * @param array $productos
     */
    public function __construct(OrdenSalidaDTO $orden, array $productos) {
        $this->orden     = $orden;
        $this->productos = $productos;
    }

    public function jsonSerialize(): array {
        return [
            'idOrden'   => $this->orden->getId(),
            'ejecutor'  => $this->orden->getNombre(),
            'fecha'     => $this->orden->getFecha(),
            'productos' => $this->productos
        ];
    }
}
