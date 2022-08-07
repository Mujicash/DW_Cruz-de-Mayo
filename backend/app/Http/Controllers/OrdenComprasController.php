<?php

namespace App\Http\Controllers;

use App\Negocio\OrdenCompraLN;
use App\Persistencia\DBOrdenCompraRepository;
use Exception;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class OrdenComprasController extends Controller {

    public function listarOrdenes() {
        $repositorio = new DBOrdenCompraRepository();
        $ordenCompLN = new OrdenCompraLN($repositorio);

        try {
            $result     = $ordenCompLN->listar();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {
        $repositorio = new DBOrdenCompraRepository();
        $ordenCompLN = new OrdenCompraLN($repositorio);

        $idUsuario = $request["id_usuario"];
        $proveedor = $request["proveedor"];
        $productos = $request["productos"];

        try {
            $ordenCompLN->registrar($idUsuario, $proveedor, $productos);
            $message    = "Se ha registrado correctamente la orden de compra";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = $e->getCode();
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }
}
