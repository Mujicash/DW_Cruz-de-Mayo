<?php

namespace App\Persistencia;

use App\Models\DTOs\DetalleCompraDTO;
use App\Models\DTOs\OrdenCompraDTO;
use App\Models\Entidades\DetalleCompra;
use App\Models\Entidades\OrdenCompra;
use App\Models\Repositorios\OrdenCompraRepository;
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
    public function getAllBranchOrders(int $idSucursal): array {
        $result  = DB::select('select O.id, O.fecha_compra, P.ruc, sum(precio * cantidad) total, O.estado from orden_compras O, proveedores P, compra_detalles CD where CD.id_compra = O.id and P.id = O.id_proveedor and O.id_sucursal = :idUsuario group by CD.id_compra', ['idUsuario' => $idSucursal]);
        $ordenes = array();

        if (empty($result)) {
            throw new Exception('No existen ordenes de compra', 204);
        }

        foreach ($result as $item) {
            $fecha     = date('d/m/Y', strtotime($item->fecha_compra));
            $estado    = ($item->estado == 0) ? 'No Entregado' : 'Entregado';
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
            throw new Exception('No existe orden de compra con el id ' . $idOrden, 204);
        }

        $fecha           = date('d/m/Y', strtotime($result[0]->fecha_compra));
        $estado          = ($result[0]->estado == 0) ? 'No Entregado' : 'Entregado';
        $nombreProveedor = $result[0]->nombre;
        $rucProveedor    = $result[0]->ruc;
        $productos       = $this->getProductsFromOrder($idOrden);

        return new DetalleCompraDTO($idOrden, $fecha, $estado, $nombreProveedor, $rucProveedor, $productos);
    }

    /**
     * @throws Exception
     */
    public function getProductsFromOrder(int $idOrden): array {
        $result    = DB::select('select CD.id_producto, PR.nombre, CD.cantidad, CD.precio
                        from orden_compras O, compra_detalles CD, productos PR
                        where O.id = :idOrden and CD.id_compra = O.id and PR.id = CD.id_producto order by CD.id_producto',
                                ['idOrden' => $idOrden]);
        $productos = array();

        if (empty($result)) {
            throw new Exception('No existen el detalle de la orden de compra con el id ' . $idOrden, 204);
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

    /**
     * @throws Exception
     */
    public function getBranch(int $idCompra) {
        $result = DB::select('select id_sucursal from orden_compras where id = :idCompra', ['idCompra' => $idCompra]);

        if (empty($result)) {
            throw new Exception('No existe orden de compra con el id ' . $idCompra, 204);
        }

        return $result[0]->id_sucursal;
    }

    /**
     * @throws Exception
     */
    public function increaseStock(int $sucursal, int $id, int $cantidad): int {
        return DB::update('update stocks set cantidad = cantidad + :cantidad
            where id_sucursal = :sucursal and id_producto = :producto', [
            'cantidad' => $cantidad,
            'sucursal' => $sucursal,
            'producto' => $id
        ]);
    }

    /**
     * @throws Exception
     */
    public function getDate(int $idCompra) {
        $result = DB::select('select fecha_compra from orden_compras where id = :idCompra', ['idCompra' => $idCompra]);

        if (empty($result)) {
            throw new Exception('No existe orden de compra con el id ' . $idCompra, 204);
        }

        //return date('d/m/Y', strtotime($result[0]->fecha_compra));
        return $result[0]->fecha_compra;
    }

    /**
     * @throws Exception
     */
    public function createGuide(string $numGuia, string $motivo, string $fechaInicio, string $fechaRec, string $imagen, int $idCompra) {
        $result = DB::insert('insert into guia_remisiones (num_guia, motivo, fecha_inicio, fecha_recepcion, img, id_compra)
            VALUES (:numGuia, :motivo, :fechaIni, NOW(), :imagen, :idCompra)', [
            'numGuia'  => $numGuia,
            'motivo'   => $motivo,
            'fechaIni' => $fechaInicio,
            //'fechaRec' => $fechaRec,
            'imagen'   => $imagen,
            'idCompra' => $idCompra
        ]);

        if (!$result) {
            throw new Exception('Error en el registro de la guia de remision.', 500);
        }
    }
}
