<?php

namespace App\Http\Controllers;

use App\Exceptions\ProductNotFoundException;
use App\Negocio\ProductoLN;
use App\Persistencia\DBProductoRepository;
use Exception;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class ProductosController extends Controller {

    public function index() {
        $repositorio = new DBProductoRepository();
        $productoLN  = new ProductoLN($repositorio);

        try {
            $result     = $productoLN->obtenerTodos();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof ProductNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {
        $repositorio = new DBProductoRepository();
        $productoLN  = new ProductoLN($repositorio);

        $nombre      = $request['nombre'];
        $laboratorio = $request['laboratorio'];
        $precioVenta = $request['precio_venta'];
        $descripcion = $request['descripcion'];
        $formato     = $request['formato'];

        try {
            $productoLN->registrar($nombre, $laboratorio, $precioVenta, $descripcion, $formato);
            $message    = "Product has been registered successfully";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = $e->getCode();
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function show(string $nombre) {
        $repositorio = new DBProductoRepository();
        $productoLN  = new ProductoLN($repositorio);

        try {
            $result     = $productoLN->obtener($nombre);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof ProductNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function update(Request $request, int $id) {
        $repositorio = new DBProductoRepository();
        $productoLN  = new ProductoLN($repositorio);

        $nombre      = $request['nombre'];
        $laboratorio = $request['laboratorio'];
        $precioVenta = $request['precio_venta'];
        $descripcion = $request['descripcion'];
        $formato     = $request['formato'];

        try {
            $productoLN->actualizar($id, $nombre, $laboratorio, $precioVenta, $descripcion, $formato);
            $message    = "Product has been successfully updated";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = ($e instanceof ProductNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function destroy(int $id) {
        $repositorio = new DBProductoRepository();
        $productoLN  = new ProductoLN($repositorio);

        try {
            $productoLN->eliminar($id);
            $message    = "Product has been successfully deleted";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = ($e instanceof ProductNotFoundException) ? $e->getCode() : 500;
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

}
