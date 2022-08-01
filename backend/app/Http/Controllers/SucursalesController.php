<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\SucursalRepository;
use App\Negocio\SucursalFinder;
use App\Persistencia\DBSucursalRepository;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class SucursalesController extends Controller {

    public function index() {
        $repository = new DBSucursalRepository();
        $finder     = new SucursalFinder($repository);

        try {
            $result     = $finder->getAll();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = $e->getCode();
        }

        return response()->json($result)->setStatusCode($statusCode);
    }

    public function store(Request $request) {}

    public function show(int $id) {}

    public function destroy(Request $request) {}
}
