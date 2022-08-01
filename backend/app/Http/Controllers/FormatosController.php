<?php

namespace App\Http\Controllers;

use App\Exceptions\FormatNotFoundException;
use App\Models\Formato;
use App\Negocio\FormatoLN;
use App\Persistencia\DBFormatoRepository;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class FormatosController extends Controller {

    public function index() {
        $repositorio = new DBFormatoRepository();
        $formatoLN   = new FormatoLN($repositorio);

        try {
            $result     = $formatoLN->obtenerTodos();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {
        $repositorio = new DBFormatoRepository();
        $formatoLN   = new FormatoLN($repositorio);
        $nombre      = $request['nombre'];

        try {
            $formatoLN->registrar($nombre);
            $message    = "Format has been registered successfully";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = ($e instanceof FormatNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function show(int $id) {
        $repositorio = new DBFormatoRepository();
        $formatoLN   = new FormatoLN($repositorio);

        try {
            $result     = $formatoLN->obtener($id);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof FormatNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function getByName(string $name) {
        $repositorio = new DBFormatoRepository();
        $formatoLN   = new FormatoLN($repositorio);

        try {
            $formato    = $formatoLN->obtenerPorNombre($name);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $formato    = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof FormatNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($formato)->setStatusCode($statusCode);
    }

}
