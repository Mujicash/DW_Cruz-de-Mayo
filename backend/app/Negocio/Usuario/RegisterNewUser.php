<?php

namespace App\Negocio\Usuario;

use App\Models\Usuario;
use App\Models\UsuarioRepository;
use App\Persistencia\DBSucursalRepository;
use App\Persistencia\DBTipoUsuarioRepository;
use Exception;

class RegisterNewUser {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function __invoke(array $datos) {

        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();
        $usuario         = new Usuario(0, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                                       $datos['apellidoMaterno'], $datos['password'],
                                       $tipoUsuarioRepo->getIdByName($datos['tipo']),
                                       $sucursalRepo->getIdByName($datos['sucursal']));

        $result = $this->repository->create($usuario);

        if (!$result) {
            throw new Exception('An error has occurred in User registration', 500);
        }
    }


}
