<?php

namespace App\Http\Controllers;

use App\Negocio\OrdenSalidaLN;
use App\Persistencia\DBOrdenSalidaRepository;
use Exception;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class OrdenSalidasController extends Controller {

    public function listarOrdenes(Request $request) {
        $repositorio = new DBOrdenSalidaRepository();
        $ordenCompLN = new OrdenSalidaLN($repositorio);
        $idUsuario   = $request["id_usuario"];

        try {
            $result     = $ordenCompLN->listar($idUsuario);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {
        $repositorio = new DBOrdenSalidaRepository();
        $ordenCompLN = new OrdenSalidaLN($repositorio);

        $idUsuario = $request["id_usuario"];
        $productos = $request["productos"];

        try {
            $ordenCompLN->registrar($idUsuario, $productos);
            $message    = "Se ha registrado correctamente la orden de salida.";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = $e->getCode();
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function show(int $id) {
        $repositorio = new DBOrdenSalidaRepository();
        $ordenCompLN = new OrdenSalidaLN($repositorio);

        try {
            $result     = $ordenCompLN->obtener($id);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

}
