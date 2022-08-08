<?php

namespace App\Negocio;

use App\Models\DetalleCompra;
use App\Models\DetalleSalida;
use App\Models\OrdenSalida;
use App\Models\OrdenSalidaRepository;
use App\Negocio\Usuario\GetUserBranch;
use App\Persistencia\DBProductoRepository;
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
        $userRepo = new DBUsuarioRepository();
        $userLN   = new GetUserBranch($userRepo);
        $branchId = $userLN->getId($idUsuario);
        $ordenSal = new OrdenSalida($idUsuario, $branchId);
        $ordenId  = $this->repository->create($ordenSal);

        $producRepo = new DBProductoRepository();
        $producLN   = new ProductoLN($producRepo);

        foreach ($productos as $producto) {
            $idProduc = $producLN->obtenerId($producto['nombre']);
            $ordenDet = new DetalleSalida($ordenId, $idProduc, $producto['cantidad']);
            $result   = $this->repository->createDetail($ordenDet);

            if (!$result) {
                throw new Exception('Ha ocurrido un error al registrar el detalle de la orden de salida.', 500);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function listar() {
        return $this->repository->getAll();
    }

    /**
     * @throws Exception
     */
    public function obtener(int $id) {
        return $this->repository->getDetail($id);
    }
}
