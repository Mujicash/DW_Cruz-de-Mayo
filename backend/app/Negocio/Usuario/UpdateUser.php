<?php

namespace App\Negocio\Usuario;

use App\Exceptions\UserNotFoundException;
use App\Models\PasswordHashLib;
use App\Models\Usuario;
use App\Models\UsuarioRepository;
use App\Persistencia\DBSucursalRepository;
use App\Persistencia\DBTipoUsuarioRepository;
use Exception;

class UpdateUser {

    private UsuarioRepository $repository;
    private PasswordHashLib   $passwordManager;

    /**
     * @param UsuarioRepository $repository
     * @param PasswordHashLib $passwordManager
     */
    public function __construct(UsuarioRepository $repository, PasswordHashLib $passwordManager) {
        $this->repository = $repository;
        $this->passwordManager = $passwordManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(int $id, array $datos) {
        //Encriptamos la contraseña
        $password        = $this->passwordManager->hash($datos['password']);
        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();

        $usuario = new Usuario($id, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                               $datos['apellidoMaterno'], $password,
                               $tipoUsuarioRepo->getIdByName($datos['tipo']),
                               $sucursalRepo->getIdByName($datos['sucursal']));

        $result  = $this->repository->update($usuario);

        if (!$result) {
            throw new UserNotFoundException('No user was found with id ' . $id, 404);
        }
    }

}
