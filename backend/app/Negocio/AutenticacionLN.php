<?php

namespace App\Negocio;

use App\Models\Repositorios\AutenticacionRepository;

class AutenticacionLN {

    private AutenticacionRepository $repository;

    public function __construct(AutenticacionRepository $repository) {
        $this->repository = $repository;
    }

    public function crearToken(int $id, string $token) {
        $this->repository->createToken($id, $token);
    }

    public function verificarToken(int $id, string $token): bool {
        return $this->repository->checkToken($id, $token);
    }

}
