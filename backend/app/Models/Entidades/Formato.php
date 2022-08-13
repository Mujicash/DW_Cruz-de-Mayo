<?php

namespace App\Models\Entidades;

use JsonSerializable;

class Formato implements JsonSerializable {

    private int    $id;
    private string $nombre;

    /**
     * @param int $id
     * @param string $nombre
     */
    public function __construct(int $id, string $nombre) {
        $this->id     = $id;
        $this->nombre = $nombre;
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

    public function jsonSerialize(): array {
        return [
            'id'     => $this->id,
            'nombre' => $this->nombre
        ];
    }
}
