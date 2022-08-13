<?php

namespace App\Models\Entidades;

use JsonSerializable;

class Proveedor implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $ruc;
    private string $telefono;
    private string $direccion;
    private string $correo;

    /**
     * @param string $nombre
     * @param string $ruc
     * @param string $direccion
     * @param string $correo
     * @param string $telefono
     * @param int $id
     */
    public function __construct(string $nombre, string $ruc, string $direccion, string $correo, string $telefono,
                                int    $id = 0) {
        $this->id        = $id;
        $this->nombre    = $nombre;
        $this->ruc       = $ruc;
        $this->direccion = $direccion;
        $this->correo    = $correo;
        $this->telefono  = $telefono;
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
    public function getRuc(): string {
        return $this->ruc;
    }

    /**
     * @return string
     */
    public function getDireccion(): string {
        return $this->direccion;
    }

    /**
     * @return string
     */
    public function getCorreo(): string {
        return $this->correo;
    }

    /**
     * @return string
     */
    public function getTelefono(): string {
        return $this->telefono;
    }

    public function jsonSerialize(): array {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'ruc'       => $this->ruc,
            'telefono'  => $this->telefono,
            'direccion' => $this->direccion,
            'correo'    => $this->correo
        ];
    }
}
