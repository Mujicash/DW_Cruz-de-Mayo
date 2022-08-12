<?php

namespace App\Negocio;

use App\Negocio\Usuario\GetUserBranch;
use App\Persistencia\DBStockRepository;
use App\Persistencia\DBUsuarioRepository;
use Exception;

class StockLN {

    private DBStockRepository $repository;

    /**
     * @param DBStockRepository $repository
     */
    public function __construct(DBStockRepository $repository) {
        $this->repository = $repository;
    }


    /**
     * @throws Exception
     */
    public function obtenerStockSucursal(int $idUsuario): array {
        $usuarioRp  = new DBUsuarioRepository();
        $usuarioLN  = new GetUserBranch($usuarioRp);
        $idSucursal = $usuarioLN->getId($idUsuario);

        return $this->repository->getAll($idSucursal);
    }
}
