<?php

namespace App\Persistencia;

use App\Models\OrdenCompraRepository;
use App\Models\RegistrarDetalleCompraDTO;
use App\Models\RegistrarOrdenCompraDTO;
use Exception;
use Illuminate\Support\Facades\DB;

class DBOrdenCompraRepository implements OrdenCompraRepository {

    /**
     * @throws Exception
     */
    public function create(RegistrarOrdenCompraDTO $ordenCompraDTO): int {
        $id = DB::table('orden_compras')->insertGetId(
            [
                'id_proveedor' => $ordenCompraDTO->getIdProveedor(),
                'id_usuario'   => $ordenCompraDTO->getIdSucursal(),
                'id_sucursal'  => $ordenCompraDTO->getIdSucursal()
            ]);

        if ($id == 0) {
            throw new Exception('Error al insertar en la base de datos.', 505);
        }

        return $id;
    }

    public function createDetail(RegistrarDetalleCompraDTO $detalleCompraDTO): bool {
        return DB::insert('INSERT INTO compra_detalles (id_compra, precio, cantidad, id_producto)
            VALUES (:idCompra, :precio, :cantidad, :idProducto)', [
            'idCompra'   => $detalleCompraDTO->getIdCompra(),
            'precio'     => $detalleCompraDTO->getPrecio(),
            'cantidad'   => $detalleCompraDTO->getCantidad(),
            'idProducto' => $detalleCompraDTO->getIdProducto()
        ]);
    }
}
