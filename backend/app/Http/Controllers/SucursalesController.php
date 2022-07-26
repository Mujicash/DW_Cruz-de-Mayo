<?php

namespace App\Http\Controllers;

use App\Models\Sucursal;
use App\Models\SucursalRepository;
use App\Negocio\SucursalFinder;
use App\Persistencia\DBSucursalRepository;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class SucursalesController extends Controller {

    public function index() {
        $repository = new DBSucursalRepository();
        $finder     = new SucursalFinder($repository);
        $result     = $finder->getAll();

        return response()->json($result['sucursales'])->setStatusCode($result['statusCode']);
    }

    public function store(Request $request) {}

    public function show(int $id) {}

    public function destroy(Request $request) {}
}
