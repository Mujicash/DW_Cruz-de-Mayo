<?php

namespace App\Models\Entidades;


use JsonSerializable;

class Producto implements JsonSerializable {

    private int    $id;
    private string $nombre;
    private string $laboratorio;
    private float  $precioVenta;
    private string $descripcion;
    private int    $idUnidad;

    /**
     * @param int $id
     * @param string $nombre
     * @param string $laboratorio
     * @param float $precioVenta
     * @param string $descripcion
     * @param int $idUnidad
     */
    public function __construct(int $id, string $nombre, string $laboratorio, float $precioVenta, string $descripcion,
                                int $idUnidad) {
        $this->id          = $id;
        $this->nombre      = $nombre;
        $this->laboratorio = $laboratorio;
        $this->precioVenta = $precioVenta;
        $this->descripcion = $descripcion;
        $this->idUnidad    = $idUnidad;
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
    public function getLaboratorio(): string {
        return $this->laboratorio;
    }

    /**
     * @return float
     */
    public function getPrecioVenta(): float {
        return $this->precioVenta;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string {
        return $this->descripcion;
    }

    /**
     * @return int
     */
    public function getIdUnidad(): int {
        return $this->idUnidad;
    }


    public function jsonSerialize(): array {
        return [
            'id'          => $this->id,
            'nombre'      => $this->nombre,
            'laboratorio' => $this->laboratorio,
            'precioVenta' => $this->precioVenta,
            'descripcion' => $this->descripcion,
            'idUnidad'    => $this->idUnidad
        ];
    }
}
