<?php

namespace App\Negocio;

use App\Models\SucursalRepository;
use Exception;

class SucursalFinder {

    private SucursalRepository $repository;

    /**
     * @param SucursalRepository $repository
     */
    public function __construct(SucursalRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll(): array {

        try {
            $sucursales = $this->repository->getAll();
            $statusCode = empty($sucursales) ? 502 : 200;
        }
        catch (Exception $e) {
            $sucursales = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'sucursales' => $sucursales,
            'statusCode' => $statusCode
        ];
    }


}
