<?php

namespace App\Http\Controllers;

use App\Negocio\Usuario\DeleteUser;
use App\Negocio\Usuario\RegisterNewUser;
use App\Negocio\Usuario\UpdateUser;
use App\Negocio\Usuario\UserFinder;
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

    public function update(Request $request, int $id) {
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
        $updater    = new UpdateUser($repository);
        $result     = $updater($id, $datos);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

    public function destroy(int $id) {
        $repository = new DBUsuarioRepository();
        $deleter    = new DeleteUser($repository);
        $result     = $deleter($id);

        return response()->json(['message' => $result['message']])->setStatusCode($result['statusCode']);
    }

    public function login(Request $request) {
        $usuario    = $request['usuario'];
        $password   = $request['password'];
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);
        $result     = $finder->checkRegisteredUser($usuario, $password);

        return response()->json($result['usuario'])->setStatusCode($result['statusCode']);
    }
}
