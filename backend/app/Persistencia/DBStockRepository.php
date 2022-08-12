<?php

namespace App\Persistencia;

use App\Models\StockDTO;
use App\Models\StockRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DBStockRepository implements StockRepository {

    /**
     * @throws Exception
     */
    public function getAll(int $idSucursal): array {
        $result    = DB::select('select s.id_producto as id, p.nombre, f.formato, s.cantidad from stocks s
                    inner join productos p on s.id_producto = p.id
                    inner join formatos f on p.id_unidad = f.id and s.id_sucursal = :idSucursal', ['idSucursal' => $idSucursal]);
        $productos = array();

        if(empty($result)) {
            throw new Exception('No hay productos en stock', 204);
        }

        foreach ($result as $i) {
            $producto = new StockDTO($i->id, $i->nombre, $i->formato, $i->cantidad);
            $productos[] = $producto;
        }

        return $productos;
    }
}
