<?php

namespace App\Negocio\Usuario;

use App\Exceptions\UserNotFoundException;
use App\Models\UsuarioRepository;
use Exception;

class DeleteUser {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws UserNotFoundException
     */
    public function __invoke(int $id) {

        $result = $this->repository->delete($id);

        if (!$result) {
            throw new UserNotFoundException('No user was found with id ' . $id, 204);
        }
    }

}
