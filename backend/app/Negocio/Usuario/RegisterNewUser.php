<?php

namespace App\Negocio\Usuario;

use App\Models\Entidades\Usuario;
use App\Models\PasswordHashLib;
use App\Models\Repositorios\UsuarioRepository;
use App\Persistencia\DBSucursalRepository;
use App\Persistencia\DBTipoUsuarioRepository;
use Exception;

class RegisterNewUser {

    private UsuarioRepository $repository;
    private PasswordHashLib   $passwordManager;

    /**
     * @param UsuarioRepository $repository
     * @param PasswordHashLib $passwordManager
     */
    public function __construct(UsuarioRepository $repository, PasswordHashLib $passwordManager) {
        $this->repository      = $repository;
        $this->passwordManager = $passwordManager;
    }

    /**
     * @throws Exception
     */
    public function __invoke(array $datos) {
        //Encriptamos la contraseña
        $password        = $this->passwordManager->hash($datos['password']);
        $tipoUsuarioRepo = new DBTipoUsuarioRepository();
        $sucursalRepo    = new DBSucursalRepository();
        $usuario         = new Usuario(0, $datos['usuario'], $datos['nombre'], $datos['apellidoPaterno'],
                                       $datos['apellidoMaterno'], $password,
                                       $tipoUsuarioRepo->getIdByName($datos['tipo']),
                                       $sucursalRepo->getIdByName($datos['sucursal']));

        $result = $this->repository->create($usuario);

        if (!$result) {
            throw new Exception('An error has occurred in User registration', 500);
        }
    }


}
