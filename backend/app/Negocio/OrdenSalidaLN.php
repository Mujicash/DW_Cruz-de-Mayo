<?php

namespace App\Negocio;

use App\Models\Entidades\DetalleSalida;
use App\Models\Entidades\OrdenSalida;
use App\Models\Repositorios\OrdenSalidaRepository;
use App\Negocio\Usuario\GetUserBranch;
use App\Persistencia\DBUsuarioRepository;
use Exception;

class OrdenSalidaLN {

    private OrdenSalidaRepository $repository;

    public function __construct(OrdenSalidaRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function registrar(int $idUsuario, array $productos) {
        //Obtenemos sucursal del usuario
        $userRepo = new DBUsuarioRepository();
        $userLN   = new GetUserBranch($userRepo);
        $branchId = $userLN->getId($idUsuario);
        $ordenSal = new OrdenSalida($idUsuario, $branchId);
        $ordenId  = $this->repository->create($ordenSal);

        foreach ($productos as $producto) {
            $ordenDet = new DetalleSalida($ordenId, $producto['id'], $producto['cantidad']);
            $result   = $this->repository->createDetail($ordenDet);

            if (!$result) {
                throw new Exception('Ha ocurrido un error al registrar el detalle de la orden de salida.', 500);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function listar(int $idUsuario) {
        $userRepo = new DBUsuarioRepository();
        $userBran = new GetUserBranch($userRepo);
        $branch   = $userBran->getId($idUsuario);

        return $this->repository->getAllBranchOrders($branch);
    }

    /**
     * @throws Exception
     */
    public function obtener(int $id) {
        return $this->repository->getDetail($id);
    }
}
