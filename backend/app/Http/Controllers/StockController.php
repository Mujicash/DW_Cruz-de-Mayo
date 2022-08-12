<?php

namespace App\Http\Controllers;

use App\Negocio\StockLN;
use App\Persistencia\DBStockRepository;
use Exception;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class StockController extends Controller {

    public function index(Request $request) {
        $repository = new DBStockRepository();
        $stockLN    = new StockLN($repository);
        $id         = $request['id_usuario'];

        try {
            $result     = $stockLN->obtenerStockSucursal($id);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($result)->setStatusCode($statusCode);
    }
}
