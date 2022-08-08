<?php

namespace App\Persistencia;

use App\Models\DetalleCompraDTO;
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
    public function create(OrdenCompra $ordenCompra): int {
        $id = DB::table('orden_compras')->insertGetId(
            [
                'id_proveedor' => $ordenCompra->getIdProveedor(),
                'id_usuario'   => $ordenCompra->getIdUsuario(),
                'id_sucursal'  => $ordenCompra->getIdSucursal()
            ]);

        if ($id == 0) {
            throw new Exception('Error al insertar en la base de datos.', 500);
        }

        return $id;
    }

    public function createDetail(DetalleCompra $detalleCompra): bool {
        return DB::insert('INSERT INTO compra_detalles (id_compra, precio, cantidad, id_producto)
            VALUES (:idCompra, :precio, :cantidad, :idProducto)', [
            'idCompra'   => $detalleCompra->getIdCompra(),
            'precio'     => $detalleCompra->getPrecio(),
            'cantidad'   => $detalleCompra->getCantidad(),
            'idProducto' => $detalleCompra->getIdProducto()
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

    /**
     * @throws Exception
     */
    public function getDetail(int $idOrden): DetalleCompraDTO {
        $result = DB::select('select O.id, O.fecha_compra, O.estado, P.nombre, P.ruc
                    from orden_compras O, proveedores P
                    where O.id = :idOrden and P.id = O.id_proveedor', ['idOrden' => $idOrden]);

        if (empty($result)) {
            throw new Exception('No existe orden de compra con el id ' . $idOrden, 404);
        }

        $fecha           = date('d/m/Y', strtotime($result[0]->fecha_compra));
        $estado          = ($result[0]->estado == 0) ? 'Pendiente' : 'Entregado';
        $nombreProveedor = $result[0]->nombre;
        $rucProveedor    = $result[0]->ruc;
        $productos       = $this->getProductsFromOrder($idOrden);

        return new DetalleCompraDTO($idOrden, $fecha, $estado, $nombreProveedor, $rucProveedor, $productos);
    }

    /**
     * @throws Exception
     */
    private function getProductsFromOrder(int $idOrden): array {
        $result    = DB::select('select CD.id_producto, PR.nombre, CD.cantidad, CD.precio
                        from orden_compras O, compra_detalles CD, productos PR
                        where O.id = :idOrden and CD.id_compra = O.id and PR.id = CD.id_producto order by CD.id_producto',
                                ['idOrden' => $idOrden]);
        $productos = array();

        if (empty($result)) {
            throw new Exception('No existen el detalle de la orden de compra con el id ' . $idOrden, 404);
        }

        foreach ($result as $i) {
            $producto = array(
                'id'       => $i->id_producto,
                'nombre'   => $i->nombre,
                'cantidad' => $i->cantidad,
                'precio'   => $i->precio
            );

            $productos[] = $producto;
        }

        return $productos;
    }
}
