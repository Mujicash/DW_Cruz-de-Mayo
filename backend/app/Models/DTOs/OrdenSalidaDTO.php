<?php

namespace App\Models\DTOs;

use JsonSerializable;

class OrdenSalidaDTO implements JsonSerializable {
    private int    $id;
    private string $nombre;
    private string $fecha;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $fecha
     */
    public function __construct(int $id, string $nombre, string $fecha) {
        $this->id     = $id;
        $this->nombre = $nombre;
        $this->fecha  = $fecha;
    }

    public function jsonSerialize(): array {
        return [
            'idOrden'  => $this->id,
            'ejecutor' => $this->nombre,
            'fecha'    => $this->fecha
        ];
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
    public function getNombre(): string {
        return $this->nombre;
    }

    /**
     * @return string
     */
    public function getFecha(): string {
        return $this->fecha;
    }
}
