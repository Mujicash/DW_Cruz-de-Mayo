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

    public function show(int $id) {
        $repositorio = new DBOrdenCompraRepository();
        $ordenCompLN = new OrdenCompraLN($repositorio);

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

    public function registrarGuia(Request $request) {
        $repositorio = new DBOrdenCompraRepository();
        $ordenCompLN = new OrdenCompraLN($repositorio);

        $idCompra = $request["id_compra"];
        $numGuia  = $request["num_guia"];
        $motivo   = $request["motivo"];
        $fechaRec = $request["fecha_recepcion"];
        $imagen   = $request["imagen"];

        try {
            $ordenCompLN->registrarGuia($idCompra, $numGuia, $motivo, $fechaRec, $imagen);
            $message    = "Se ha registrado correctamente la guia de remision de la orden " . $idCompra;
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = $e->getCode();
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }
}
