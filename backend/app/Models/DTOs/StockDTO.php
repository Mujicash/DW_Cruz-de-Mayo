<?php

namespace App\Models\DTOs;

use JsonSerializable;

class StockDTO implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $formato;
    private int    $cantidad;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $formato
     * @param int $cantidad
     */
    public function __construct(int $id, string $nombre, string $formato, int $cantidad) {
        $this->id       = $id;
        $this->nombre   = $nombre;
        $this->formato  = $formato;
        $this->cantidad = $cantidad;
    }

    public function jsonSerialize(): array {
        return [
            'id'       => $this->id,
            'nombre'   => $this->nombre,
            'formato'  => $this->formato,
            'cantidad' => $this->cantidad
        ];
    }
}
