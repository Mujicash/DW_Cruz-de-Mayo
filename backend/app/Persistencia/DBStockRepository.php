<?php

namespace App\Persistencia;

use App\Models\DTOs\StockDTO;
use App\Models\Repositorios\StockRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DBStockRepository implements StockRepository {

    /**
     * @throws Exception
     */
    public function getAll(int $idSucursal): array {
        $result    = DB::select('select s.id_producto as id, p.nombre, f.formato, s.cantidad from stocks s
                    inner join productos p on s.id_producto = p.id
                    inner join formatos f on p.id_unidad = f.id and s.id_sucursal = :idSucursal',
                                ['idSucursal' => $idSucursal]);
        $productos = array();

        if (empty($result)) {
            throw new Exception('No hay productos en stock', 204);
        }

        foreach ($result as $i) {
            $producto    = new StockDTO($i->id, $i->nombre, $i->formato, $i->cantidad);
            $productos[] = $producto;
        }

        return $productos;
    }

    public function disminuirStock() {
        $result    = DB::select('select o.id, o.id_sucursal, ds.id_producto, ds.cantidad from orden_salidas o inner join detalle_salidas ds on o.id = ds.id_salida');

        foreach ($result as $i) {
            DB::update('update stocks set cantidad = cantidad - :cant where id_sucursal = :idSu and id_producto = :idPr', [
                'cant' => $i->cantidad,
                'idSu' => $i->id_sucursal,
                'idPr' => $i->id_producto
            ]);
        }
    }
}
