<?php

namespace App\Persistencia;

use App\Models\OrdenCompraDTO;
use App\Models\OrdenCompraRepository;
use App\Models\DetalleCompra;
use App\Models\OrdenCompra;
use Exception;
use Illuminate\Support\Facades\DB;

class DBOrdenCompraRepository implements OrdenCompraRepository {

    /**
     * @throws Exception
     */
    public function create(OrdenCompra $ordenCompraDTO): int {
        $id = DB::table('orden_compras')->insertGetId(
            [
                'id_proveedor' => $ordenCompraDTO->getIdProveedor(),
                'id_usuario'   => $ordenCompraDTO->getIdUsuario(),
                'id_sucursal'  => $ordenCompraDTO->getIdSucursal()
            ]);

        if ($id == 0) {
            throw new Exception('Error al insertar en la base de datos.', 505);
        }

        return $id;
    }

    public function createDetail(DetalleCompra $detalleCompraDTO): bool {
        return DB::insert('INSERT INTO compra_detalles (id_compra, precio, cantidad, id_producto)
            VALUES (:idCompra, :precio, :cantidad, :idProducto)', [
            'idCompra'   => $detalleCompraDTO->getIdCompra(),
            'precio'     => $detalleCompraDTO->getPrecio(),
            'cantidad'   => $detalleCompraDTO->getCantidad(),
            'idProducto' => $detalleCompraDTO->getIdProducto()
        ]);
    }

    /**
     * @throws Exception
     */
    public function getAll(): array {
        $result  = DB::select('select O.id, O.fecha_compra, P.ruc, sum(precio * cantidad) total, O.estado from orden_compras O, proveedores P, compra_detalles CD where CD.id_compra = O.id and P.id = O.id_proveedor group by CD.id_compra');
        $ordenes = array();

        if (empty($result)) {
            throw new Exception('No existen ordenes de compra', 404);
        }

        foreach ($result as $item) {
            $fecha     = date('d/m/Y', strtotime($item->fecha_compra));
            $estado    = ($item->estado == 0) ? 'Pendiente' : 'Entregado';
            $total     = round($item->total, 1);
            $orden     = new OrdenCompraDTO($item->id, $fecha, $item->ruc, $total, $estado);
            $ordenes[] = $orden;
        }

        return $ordenes;
    }
}
