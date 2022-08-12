<?php

namespace App\Models;

use JsonSerializable;

class Sucursal implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $direccion;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $direccion
     */
    public function __construct(int $id = 0, string $nombre, string $direccion) {
        $this->id        = $id;
        $this->nombre    = $nombre;
        $this->direccion = $direccion;
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
    public function getDireccion(): string {
        return $this->direccion;
    }

    public function jsonSerialize(): array {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'direccion' => $this->direccion
        ];
    }

}
