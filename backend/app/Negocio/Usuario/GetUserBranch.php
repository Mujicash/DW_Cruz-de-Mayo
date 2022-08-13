<?php

namespace App\Negocio\Usuario;

use App\Models\Repositorios\UsuarioRepository;
use Exception;

class GetUserBranch {

    private UsuarioRepository $repository;

    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function getId(int $userId): int {
        return $this->repository->getBranchIdByUserId($userId);
    }

}
