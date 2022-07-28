<?php

namespace App\Negocio\Usuario;

use App\Models\Usuario;
use App\Models\UsuarioRepository;
use App\Persistencia\DBSucursalRepository;
use App\Persistencia\DBTipoUsuarioRepository;
use Exception;

class UpdateUser {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke(int $id, array $datos): array {
        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();
        //hashing password

        try {
            $usuario = new Usuario($id, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                                   $datos['apellidoMaterno'], $datos['password'],
                                   $tipoUsuarioRepo->getIdByName($datos['tipo']),
                                   $sucursalRepo->getIdByName($datos['sucursal']));

            if ($this->repository->update($usuario)) {
                $message    = "User has been successfully updated";
                $statusCode = 200;
            }
            else {
                $message    = "User is not found with id " . $id;
                $statusCode = 404;
            }
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message'    => $message,
            'statusCode' => $statusCode
        ];
    }

}
