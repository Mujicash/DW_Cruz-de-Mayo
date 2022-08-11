<?php

namespace App\Negocio;

use App\Models\DetalleCompraDTO;
use App\Models\OrdenCompraRepository;
use App\Models\DetalleCompra;
use App\Models\OrdenCompra;
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
    public function registrar(int $idUsuario, int $proveedor, array $productos) {
        //Obtenemos la sucursal del usuario
        $userRepo = new DBUsuarioRepository();
        $userLN   = new GetUserBranch($userRepo);
        $branchId = $userLN->getId($idUsuario);
        //Registrar orden de compra
        $ordenCom = new OrdenCompra($idUsuario, $branchId, $proveedor);
        $ordenId  = $this->repository->create($ordenCom);

        foreach ($productos as $producto) {
            $ordenDet = new DetalleCompra($ordenId, $producto['id'], $producto['precio'], $producto['cantidad']);
            $result   = $this->repository->createDetail($ordenDet);

            if (!$result) {
                throw new Exception('Ha ocurrido un error al registrar el detalle de la compra', 500);
            }
        }
    }

    /**
     * @throws Exception
     */
    public function listar(): array {
        return $this->repository->getAll();
    }

    /**
     * @throws Exception
     */
    public function obtener(int $id): DetalleCompraDTO {
        return $this->repository->getDetail($id);
    }

    /**
     * @throws Exception
     */
    public function registrarGuia(int $idCompra, string $numGuia, string $motivo, string $fechaRec, $imagen) {
        //Obtener fecha inicio
        $fechaInicio = $this->repository->getDate($idCompra);
        //dd($fechaInicio);
        //Guardar imagen
        $imagen = $this->cargarFoto($imagen, $numGuia);
        //Registrar Guia
        $this->repository->createGuide($numGuia, $motivo, $fechaInicio, $fechaRec, $imagen, $idCompra);

        $productos = $this->repository->getProductsFromOrder($idCompra);
        $sucursal = $this->repository->getBranch($idCompra);
        //Aumentamos stock
        foreach ($productos as $producto) {
            $result = $this->repository->increaseStock($sucursal, $producto['id'], $producto["cantidad"]);

            if (!$result) {
                throw new Exception('Ha ocurrido un error al registrar el detalle de la compra', 500);
            }
        }
    }

    private function cargarFoto($file, $numGuia): string {
        $nombre = time() . "-" . $numGuia . "." . $file->getClientOriginalExtension();
        $file->move(base_path('/public/images/guias_remision'), $nombre);

        return $nombre;
    }

}
