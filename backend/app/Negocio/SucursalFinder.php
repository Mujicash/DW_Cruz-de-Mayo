<?php

namespace App\Negocio;

use App\Models\SucursalRepository;

class SucursalFinder {

    private SucursalRepository $repository;

    /**
     * @param SucursalRepository $repository
     */
    public function __construct(SucursalRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll(): array {
        $sucursales = $this->repository->getAll();
        $statusCode = empty($sucursales) ? 502 : 200;

        return [
            'sucursales' => $sucursales,
            'statusCode' => $statusCode
        ];
    }


}
