<?php

namespace App\Negocio\Usuario;

use App\Exceptions\UserNotFoundException;
use App\Models\Usuario;
use App\Models\UsuarioRepository;
use Exception;

class UserFinder {

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
    public function checkRegisteredUser(string $username, string $password): Usuario {
        //hashing password

        return $this->repository->validate($username, $password);
    }

}
