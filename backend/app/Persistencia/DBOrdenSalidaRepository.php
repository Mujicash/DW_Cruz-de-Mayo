<?php

namespace App\Persistencia;

use App\Models\DTOs\DetalleSalidaDTO;
use App\Models\DTOs\OrdenSalidaDTO;
use App\Models\Entidades\DetalleSalida;
use App\Models\Entidades\OrdenSalida;
use App\Models\Repositorios\OrdenSalidaRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DBOrdenSalidaRepository implements OrdenSalidaRepository {

    /**
     * @throws Exception
     */
    public function create(OrdenSalida $ordenSalida): int {
        $id = DB::table('orden_salidas')->insertGetId(
            [
                'id_usuario'  => $ordenSalida->getIdUsuario(),
                'id_sucursal' => $ordenSalida->getIdSucursal()
            ]);

        if ($id == 0) {
            throw new Exception('Error al insertar en la base de datos.', 500);
        }

        return $id;
    }

    public function createDetail(DetalleSalida $detalleSalida): bool {
        return DB::insert('INSERT INTO detalle_salidas (id_salida, cantidad, id_producto)
            VALUES (:idSalida, :cantidad, :idProducto)', [
            'idSalida'   => $detalleSalida->getIdSalida(),
            'cantidad'   => $detalleSalida->getCantidad(),
            'idProducto' => $detalleSalida->getIdProducto()
        ]);
    }

    /**
     * @throws Exception
     */
    public function getAllBranchOrders(int $idSucursal): array {
        $result  = DB::select('select O.id, U.nombre, O.fecha from orden_salidas O, usuarios U where U.id = O.id_usuario and O.id_sucursal = :idSucursal', ['idSucursal' => $idSucursal]);
        $ordenes = array();

        if (empty($result)) {
            throw new Exception('No existen ordenes de salida.', 204);
        }

        foreach ($result as $item) {
            $fecha     = date('d/m/Y', strtotime($item->fecha));
            $orden     = new OrdenSalidaDTO($item->id, $item->nombre, $fecha);
            $ordenes[] = $orden;
        }

        return $ordenes;
    }

    /**
     * @throws Exception
     */
    public function getDetail(int $id): DetalleSalidaDTO {
        $result = DB::select('select O.id, U.nombre, O.fecha from orden_salidas O, usuarios U where O.id = :idOrden and U.id = O.id_usuario',
                             ['idOrden' => $id]);

        if (empty($result)) {
            throw new Exception('No existe orden de salida con el id ' . $id, 204);
        }

        $fecha     = date('d/m/Y', strtotime($result[0]->fecha));
        $orden     = new OrdenSalidaDTO($result[0]->id, $result[0]->nombre, $fecha);
        $productos = $this->getProductsFromOrder($id);

        return new DetalleSalidaDTO($orden, $productos);
    }

    /**
     * @throws Exception
     */
    private function getProductsFromOrder($idOrden): array {
        $result    = DB::select('select DS.id_producto, P.nombre, DS.cantidad from orden_salidas O, detalle_salidas DS, productos P
                        where O.id = :idOrden and DS.id_salida = O.id and P.id = DS.id_producto order by DS.id_producto',
                                ['idOrden' => $idOrden]);
        $productos = array();

        if (empty($result)) {
            throw new Exception('No existen el detalle de la orden de salida con el id ' . $idOrden, 204);
        }

        foreach ($result as $i) {
            $producto = array(
                'id'       => $i->id_producto,
                'nombre'   => $i->nombre,
                'cantidad' => $i->cantidad
            );

            $productos[] = $producto;
        }

        return $productos;
    }
}
