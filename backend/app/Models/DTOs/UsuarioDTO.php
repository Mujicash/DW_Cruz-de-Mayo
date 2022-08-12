<?php

namespace App\Models;

use JsonSerializable;

class UsuarioDTO implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $usuario;
    private string $tipoUsuario;
    private string $sucursal;
    private string $direccion;
    private string $apiToken = '';

    /**
     * @param int $id
     * @param string $nombre
     * @param string $usuario
     * @param string $tipoUsuario
     * @param string $sucursal
     * @param string $direccion
     */
    public function __construct(int $id, string $nombre, string $usuario, string $tipoUsuario, string $sucursal, string $direccion = '') {
        $this->id        = $id;
        $this->nombre    = $nombre;
        $this->usuario   = $usuario;
        $this->sucursal  = $sucursal;
        $this->direccion = $direccion;
        $this->tipoUsuario  = $tipoUsuario;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param string $apiToken
     */
    public function setApiToken(string $apiToken): void {
        $this->apiToken = $apiToken;
    }

    public function jsonSerialize(): array {
        return [
            'id'        => $this->id,
            'nombre'    => $this->nombre,
            'usuario'   => $this->usuario,
            'tipo'      => $this->tipoUsuario,
            'sucursal'  => $this->sucursal,
            'direccion' => $this->direccion,
            'apiToken'  => $this->apiToken
        ];
    }
}
