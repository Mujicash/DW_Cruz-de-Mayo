<?php

namespace App\Negocio;

use App\Exceptions\SupplierNotFoundException;
use App\Models\Proveedor;
use App\Models\ProveedorRepository;
use Exception;

class ProveedorLN {

    private ProveedorRepository $repository;

    /**
     * @param ProveedorRepository $repository
     */
    public function __construct(ProveedorRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function registrar($nombre, $ruc, $telefono, $direccion, $correo) {
        $proveedor = new Proveedor($nombre, $ruc, $direccion, $correo, $telefono);
        $result    = $this->repository->create($proveedor);

        if (!$result) {
            throw new Exception('Error al registrar el proveedor en el sistema', 500);
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
    public function obtener(string $nombre): array {
        return $this->repository->getByName($nombre);
    }

    /**
     * @throws Exception
     */
    public function actualizar($id, $nombre, $ruc, $telefono, $direccion, $correo) {
        $proveedor = new Proveedor($nombre, $ruc, $direccion, $correo, $telefono, $id);
        $result = $this->repository->update($proveedor);

        if(!$result) {
            throw new SupplierNotFoundException('No se encontro al proveedor con id '. $id, 404);
        }
    }

    /**
     * @throws Exception
     */
    public function eliminar($id) {
        $result = $this->repository->delete($id);

        if(!$result) {
            throw new SupplierNotFoundException('No se encontro al proveedor con id '. $id, 404);
        }
    }

    /**
     * @throws Exception
     */
    public function obtenerId(string $proveedor) {
        return $this->repository->getId($proveedor);
    }

}
