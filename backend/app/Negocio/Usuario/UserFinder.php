<?php

namespace App\Negocio\Usuario;

use App\Models\DTOs\UsuarioDTO;
use App\Models\Entidades\Usuario;
use App\Models\PasswordHashLib;
use App\Models\Repositorios\UsuarioRepository;
use App\Negocio\AutenticacionLN;
use App\Persistencia\DBAutenticacionRepository;
use Exception;
use Illuminate\Support\Str;

class UserFinder {

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
    public function getAll(): array {
        return $this->repository->getAll();
    }

    /**
     * @param $id
     * @return Usuario
     * @throws Exception
     */
    public function getById($id): Usuario {
        return $this->repository->getById($id);
    }

    /**
     * @throws Exception
     */
    public function checkRegisteredUser(string $username, string $password): UsuarioDTO {
        //hashing password
        $password = $this->passwordManager->hash($password);
        $usuario  = $this->repository->validate($username, $password);

        $autRepo = new DBAutenticacionRepository();
        $autenLN = new AutenticacionLN($autRepo);

        //dd($usuario->jsonSerialize());
        $token = $this->passwordManager->hash(Str::random(150) . $username . $password);
        $autenLN->crearToken($usuario->getId(), $token);
        $usuario->setApiToken($token);

        return $usuario;
    }

}
