<?php

namespace App\Http\Controllers;

use App\Exceptions\UserNotFoundException;
use App\Negocio\Usuario\DeleteUser;
use App\Negocio\Usuario\RegisterNewUser;
use App\Negocio\Usuario\UpdateUser;
use App\Negocio\Usuario\UserFinder;
use App\Persistencia\DBUsuarioRepository;
use Exception;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller;

class UsuariosController extends Controller {

    public function index() {
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);

        try {
            $result     = $finder->getAll();
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof UserNotFoundException) ? 404 : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
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

        try {
            $register($datos);
            $message    = "User has been registered successfully";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = $e->getCode();
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function show(int $id) {
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);

        try {
            $result     = $finder->getById($id);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof UserNotFoundException) ? 404 : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
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

        try {
            $updater($id, $datos);
            $message    = "User has been successfully updated";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = ($e instanceof UserNotFoundException) ? 404 : 500;
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function destroy(int $id) {
        $repository = new DBUsuarioRepository();
        $deleter    = new DeleteUser($repository);

        try {
            $deleter($id);
            $message    = "User has been successfully deleted";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = ($e instanceof UserNotFoundException) ? 404 : 500;
        }

        return response()->json(['message' => $message])->setStatusCode($statusCode);
    }

    public function login(Request $request) {
        $usuario    = $request['usuario'];
        $password   = $request['password'];
        $repository = new DBUsuarioRepository();
        $finder     = new UserFinder($repository);

        try {
            $result     = $finder->checkRegisteredUser($usuario, $password);
            $statusCode = 200;
        }
        catch (Exception $e) {
            $result     = array('Error' => $e->getMessage());
            $statusCode = ($e instanceof UserNotFoundException) ? 404 : 500;
        }

        return response()->json($result)->setStatusCode($statusCode);
    }
}
