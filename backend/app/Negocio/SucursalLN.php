<?php

namespace App\Negocio;

use App\Models\SucursalRepository;
use Exception;

class SucursalLN {

    private SucursalRepository $repository;

    /**
     * @param SucursalRepository $repository
     */
    public function __construct(SucursalRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array {

        return $this->repository->getAll();

    }


}
