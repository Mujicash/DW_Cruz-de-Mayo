<?php

namespace App\Negocio;

use App\Models\Usuario;
use App\Models\UsuarioRepository;
use App\Persistencia\DBSucursalRepository;
use App\Persistencia\DBTipoUsuarioRepository;
use Exception;
use Illuminate\Database\QueryException;

class RegisterNewUser {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke(array $datos): array {

        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();

        try {
            $usuario = new Usuario(0, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                                   $datos['apellidoMaterno'], $datos['password'],
                                   $tipoUsuarioRepo->getIdByName($datos['tipo']),
                                   $sucursalRepo->getIdByName($datos['sucursal']));

            if($this->repository->create($usuario)) {
                $message    = "Product has been registered successfully";
                $statusCode = 200;
            }
            else {
                $message    = "An error has occurred ";
                $statusCode = 502;
            }
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message' => $message,
            'statusCode' => $statusCode
        ];
    }


}
