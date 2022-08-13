<?php

namespace App\Models\Entidades;


use JsonSerializable;

class Usuario implements JsonSerializable {

    private int    $id;
    private string $usuario;
    private string $nombre;
    private string $apellidoPaterno;
    private string $apellidoMaterno;
    private string $password;
    private int    $tipo;
    private int    $sucursal;

    /**
     * @param int $id
     * @param string $usuario
     * @param string $nombre
     * @param string $apellidoPaterno
     * @param string $apellidoMaterno
     * @param string $password
     * @param int $tipo
     * @param int $sucursal
     */
    public function __construct( int $id, string $usuario, string $nombre, string $apellidoPaterno, string $apellidoMaterno, string $password, int $tipo, int $sucursal) {
        $this->id              = $id;
        $this->usuario         = $usuario;
        $this->nombre          = $nombre;
        $this->apellidoPaterno = $apellidoPaterno;
        $this->apellidoMaterno = $apellidoMaterno;
        $this->password        = $password;
        $this->tipo            = $tipo;
        $this->sucursal        = $sucursal;
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
    public function getUsuario(): string {
        return $this->usuario;
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
    public function getApellidoPaterno(): string {
        return $this->apellidoPaterno;
    }

    /**
     * @return string
     */
    public function getApellidoMaterno(): string {
        return $this->apellidoMaterno;
    }

    /**
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    public function getTipo(): int {
        return $this->tipo;
    }

    public function getSucursal(): int {
        return $this->sucursal;
    }

    public function jsonSerialize(): array {
        return [
            'id'              => $this->id,
            'usuario'         => $this->usuario,
            'nombre'          => $this->nombre,
            'apellidoPaterno' => $this->apellidoPaterno,
            'apellidoMaterno' => $this->apellidoMaterno,
            'password'        => $this->password,
            'tipo'            => $this->tipo,
            'sucursal'        => $this->sucursal
        ];
    }
}
