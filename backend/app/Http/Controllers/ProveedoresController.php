<?php

namespace App\Http\Controllers;

use App\Exceptions\SupplierNotFoundException;
use App\Negocio\ProveedorLN;
use App\Persistencia\DBProveedorRepository;
use Exception;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class ProveedoresController extends Controller {

    public function index() {
        $repositorio = new DBProveedorRepository();
        $proveedorLN = new ProveedorLN($repositorio);

        try {
            $result     = $proveedorLN->listar();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof SupplierNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {
        $repositorio = new DBProveedorRepository();
        $proveedorLN = new ProveedorLN($repositorio);

        $nombre    = $request['nombre'];
        $ruc       = $request['ruc'];
        $telefono  = $request['telefono'];
        $direccion = $request['direccion'];
        $correo    = $request['correo'];

        try {
            $proveedorLN->registrar($nombre, $ruc, $telefono, $direccion, $correo);
            $message    = "Se ha registrado satisfactoriamente al proveedor.";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($message)->setStatusCode($statusCode);
    }

    public function show(string $nombre) {
        $repositorio = new DBProveedorRepository();
        $proveedorLN = new ProveedorLN($repositorio);

        try {
            $result     = $proveedorLN->obtener($nombre);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof SupplierNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function update(Request $request, int $id) {
        $repositorio = new DBProveedorRepository();
        $proveedorLN = new ProveedorLN($repositorio);

        $nombre    = $request['nombre'];
        $ruc       = $request['ruc'];
        $telefono  = $request['telefono'];
        $direccion = $request['direccion'];
        $correo    = $request['correo'];

        try {
            $proveedorLN->actualizar($id, $nombre, $ruc, $telefono, $direccion, $correo);
            $message    = "Se ha actualizado satisfactoriamente al proveedor.";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($message)->setStatusCode($statusCode);
    }

    public function destroy(int $id) {
        $repositorio = new DBProveedorRepository();
        $proveedorLN = new ProveedorLN($repositorio);

        try {
            $proveedorLN->eliminar($id);
            $message    = "Se ha eliminado satisfactoriamente al proveedor.";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($message)->setStatusCode($statusCode);
    }
}
