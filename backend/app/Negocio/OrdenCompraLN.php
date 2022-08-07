<?php

namespace App\Negocio;

use App\Models\OrdenCompraRepository;
use App\Models\RegistrarDetalleCompraDTO;
use App\Models\RegistrarOrdenCompraDTO;
use App\Negocio\Usuario\GetUserBranch;
use App\Persistencia\DBProductoRepository;
use App\Persistencia\DBProveedorRepository;
use App\Persistencia\DBUsuarioRepository;
use Exception;

class OrdenCompraLN {

    private OrdenCompraRepository $repository;

    /**
     * @param OrdenCompraRepository $repository
     */
    public function __construct(OrdenCompraRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function registrar(int $idUsuario, string $proveedor, array $productos) {
        $userRepo = new DBUsuarioRepository();
        $userLN   = new GetUserBranch($userRepo);
        $branchId = $userLN->getId($idUsuario);
        $provRepo = new DBProveedorRepository();
        $provLN   = new ProveedorLN($provRepo);
        $proveId  = $provLN->obtenerId($proveedor);
        $ordenCom = new RegistrarOrdenCompraDTO($idUsuario, $branchId, $proveId);
        $ordenId  = $this->repository->create($ordenCom);

        $producRepo = new DBProductoRepository();
        $producLN   = new ProductoLN($producRepo);

        foreach ($productos as $producto) {
            $idProduc = $producLN->obtenerId($producto['nombre']);
            $ordenDet = new RegistrarDetalleCompraDTO($ordenId, $idProduc, $producto['precio'], $producto['cantidad']);
            $result   = $this->repository->createDetail($ordenDet);

            if (!$result) {
                throw new Exception('Ha ocurrido un error al registrar el detalle de la compra', 500);
            }
        }

    }

}
