<?php

namespace App\Models;

use JsonSerializable;

class DetalleCompraDTO implements JsonSerializable {

    private int    $idOrden;
    private string $fechaCompra;
    private string $estado;
    private string $nombreProveedor;
    private string $rucProveedor;
    private array  $productos;

    /**
     * @param int $idOrden
     * @param string $fechaCompra
     * @param string $estado
     * @param string $nombreProveedor
     * @param string $rucProveedor
     * @param array $productos
     */
    public function __construct(int $idOrden, string $fechaCompra, string $estado, string $nombreProveedor, string $rucProveedor, array $productos) {
        $this->idOrden         = $idOrden;
        $this->fechaCompra     = $fechaCompra;
        $this->estado          = $estado;
        $this->nombreProveedor = $nombreProveedor;
        $this->rucProveedor    = $rucProveedor;
        $this->productos       = $productos;
    }

    public function jsonSerialize(): array {
        return [
            'id'               => $this->idOrden,
            'fecha_compra'     => $this->fechaCompra,
            'estado'           => $this->estado,
            'nombre_proveedor' => $this->nombreProveedor,
            'ruc_proveedor'    => $this->rucProveedor,
            'productos'        => $this->productos
        ];
    }
}
