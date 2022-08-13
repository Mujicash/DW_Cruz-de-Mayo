<?php

namespace App\Models\DTOs;

use JsonSerializable;

class ProductoDTO implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $unidad;
    private string $laboratorio;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $unidad
     * @param string $laboratorio
     */
    public function __construct(int $id, string $nombre, string $unidad, string $laboratorio = '') {
        $this->id          = $id;
        $this->nombre      = $nombre;
        $this->unidad      = $unidad;
        $this->laboratorio = $laboratorio;
    }

    public function jsonSerialize(): array {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'unidad'      => $this->unidad,
            'laboratorio' => $this->laboratorio
        ];
    }
}
