<?php

namespace App\Http\Controllers;

use App\Negocio\ProductoLN;
use App\Persistencia\DBProductoRepository;
use Illuminate\Http\Request;
use \Laravel\Lumen\Routing\Controller;

class ProductosController extends Controller {

    private ProductoLN $ln;

    public function __construct() {
        $this->ln = new ProductoLN(new DBProductoRepository());
    }

    public function index() {
        $result = $this->ln->obtenerTodos();

        return response()->json($result['productos'])->setStatusCode($result['statusCode']);
    }

    public function store(Request $request) {
        $nombre      = $request['nombre'];
        $laboratorio = $request['laboratorio'];
        $precioVenta = $request['precio_venta'];
        $descripcion = $request['descripcion'];
        $formato     = $request['formato'];

        $result = $this->ln->registrar($nombre, $laboratorio, $precioVenta, $descripcion, $formato);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

    public function show(string $nombre) {
        $result = $this->ln->obtener($nombre);

        return response()->json($result['producto'])->setStatusCode($result['statusCode']);
    }

    public function update(Request $request, int $id) {
        $nombre      = $request['nombre'];
        $laboratorio = $request['laboratorio'];
        $precioVenta = $request['precio_venta'];
        $descripcion = $request['descripcion'];
        $formato     = $request['formato'];

        $result = $this->ln->actualizar($id, $nombre, $laboratorio, $precioVenta, $descripcion, $formato);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

    public function destroy(int $id) {
        $result = $this->ln->eliminar($id);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

}
