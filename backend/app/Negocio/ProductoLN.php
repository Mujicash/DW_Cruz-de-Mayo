<?php

namespace App\Negocio;

use App\Exceptions\ProductNotFoundException;
use App\Models\Entidades\Producto;
use App\Models\Repositorios\ProductoRepository;
use App\Persistencia\DBFormatoRepository;
use Exception;

class ProductoLN {

    private ProductoRepository $repository;

    /**
     * @param ProductoRepository $repository
     */
    public function __construct(ProductoRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function registrar($nombre, $laboratorio, $precio, $descripcion, $formato) {
        $ln        = new FormatoLN(new DBFormatoRepository());
        $idFormato = $ln->obtenerPorNombre($formato)->getId();
        $producto  = new Producto(0, $nombre, $laboratorio, $precio, $descripcion, $idFormato);
        $result    = $this->repository->create($producto);

        if (!$result) {
            throw new Exception('An error has occurred in the Product registration', 500);
        }
    }

    public function obtener(int $id): Producto {
        return $this->repository->getById($id);
    }

    public function obtenerTodos(): array {
        return $this->repository->getAll();
    }

    /**
     * @throws ProductNotFoundException
     * @throws Exception
     */
    public function actualizar($id, $nombre, $laboratorio, $precio, $descripcion, $formato) {
        $ln        = new FormatoLN(new DBFormatoRepository());
        $idFormato = $ln->obtenerPorNombre($formato)->getId();
        $producto = new Producto($id, $nombre, $laboratorio, $precio, $descripcion, $idFormato);
        $result   = $this->repository->update($producto);

        if (!$result) {
            throw new ProductNotFoundException("Product is not found with id " . $id, 204);
        }
    }

    /**
     * @throws ProductNotFoundException
     */
    public function eliminar(int $id) {
        $result = $this->repository->delete($id);

        if (!$result) {
            throw new ProductNotFoundException("Product is not found with id " . $id);
        }
    }

    /**
     * @param string $nombre
     * @return mixed
     */
    public function obtenerId(string $nombre) {
        return $this->repository->getId($nombre);
    }

}
