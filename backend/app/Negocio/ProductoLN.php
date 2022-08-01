<?php

namespace App\Negocio;

use App\Exceptions\ProductNotFoundException;
use App\Models\Producto;
use App\Models\ProductoRepository;
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

    public function obtener(string $nombre): array {
        return $this->repository->getByName($nombre);
    }

    public function obtenerTodos(): array {
        return $this->repository->getAll();
    }

    /**
     * @throws ProductNotFoundException
     */
    public function actualizar($id, $nombre, $laboratorio, $precio, $descripcion, $formato) {

        $producto = new Producto($id, $nombre, $laboratorio, $precio, $descripcion, $formato);
        $result   = $this->repository->update($producto);

        if (!$result) {
            throw new ProductNotFoundException("Product is not found with id " . $id, 404);
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

}
