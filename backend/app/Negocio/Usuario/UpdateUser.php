<?php

namespace App\Negocio\Usuario;

use App\Exceptions\UserNotFoundException;
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

    /**
     * @throws Exception
     */
    public function __invoke(int $id, array $datos) {
        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();
        //hashing password

        $usuario = new Usuario($id, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                               $datos['apellidoMaterno'], $datos['password'],
                               $tipoUsuarioRepo->getIdByName($datos['tipo']),
                               $sucursalRepo->getIdByName($datos['sucursal']));

        $result  = $this->repository->update($usuario);

        if (!$result) {
            throw new UserNotFoundException('No user was found with id ' . $id, 404);
        }
    }

}
