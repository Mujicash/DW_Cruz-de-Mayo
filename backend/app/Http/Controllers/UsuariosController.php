<?php

namespace App\Http\Controllers;

use App\Negocio\RegisterNewUser;
use App\Negocio\UserFinder;
use App\Persistencia\DBUsuarioRepository;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class UsuariosController extends Controller {

    public function index() {
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);
        $result     = $finder->getAll();

        return response()->json($result['usuarios'])->setStatusCode($result['statusCode']);
    }

    public function store(Request $request) {
        $datos = [
            'usuario'         => $request->usuario,
            'nombre'          => $request->nombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'password'        => $request->password,
            'tipo'            => $request->tipo,
            'sucursal'        => $request->sucursal
        ];

        $repository = new DBUsuarioRepository();
        $register   = new RegisterNewUser($repository);
        $result     = $register($datos);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

    public function show(int $id) {
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);
        $result     = $finder->getById($id);

        return response()->json($result['usuario'])->setStatusCode($result['statusCode']);
    }

    public function destroy(Request $request) {}

    public function login(Request $request) {
        $usuario    = $request['usuario'];
        $password   = $request['password'];
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);
        $result     = $finder->checkRegisteredUser($usuario, $password);

        return response()->json($result['usuario'])->setStatusCode($result['statusCode']);
    }
}
