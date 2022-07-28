<?php

namespace App\Negocio;

use App\Exceptions\ProductNotFoundException;
use App\Models\Producto;
use App\Models\ProductoRepository;
use Exception;

class ProductoLN {

    private ProductoRepository $repository;

    /**
     * @param ProductoRepository $repository
     */
    public function __construct(ProductoRepository $repository) {
        $this->repository = $repository;
    }

    public function registrar($nombre, $laboratorio, $precio, $descripcion, $formato): array {
        $producto = new Producto(0, $nombre, $laboratorio, $precio, $descripcion, $formato);

        try {
            $result = $this->repository->create($producto);

            if (!$result) {
                throw new Exception('An error has occurred in the Product registration');
            }

            $message    = "Product has been registered successfully";
            $statusCode = 200;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message'    => $message,
            'statusCode' => $statusCode
        ];
    }

    public function obtener(string $nombre): array {
        try {
            $producto   = $this->repository->getByName($nombre);
            $statusCode = 200;
        }
        catch (ProductNotFoundException $me) {
            $producto   = array('Error' => $me->getMessage());
            $statusCode = 404;
        }
        catch (Exception $e) {
            $producto   = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'producto'   => $producto,
            'statusCode' => $statusCode
        ];
    }

    public function obtenerTodos(): array {
        try {
            $productos   = $this->repository->getAll();
            $statusCode = 200;
        }
        catch (ProductNotFoundException $me) {
            $productos   = array('Error' => $me->getMessage());
            $statusCode = 404;
        }
        catch (Exception $e) {
            $productos   = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'productos'   => $productos,
            'statusCode' => $statusCode
        ];
    }

    public function actualizar($id, $nombre, $laboratorio, $precio, $descripcion, $formato): array {

        try {
            $producto = new Producto($id, $nombre, $laboratorio, $precio, $descripcion, $formato);
            $result   = $this->repository->update($producto);

            if (!$result) {
                throw new ProductNotFoundException("Product is not found with id " . $id);
            }

            $message    = "Product has been successfully updated";
            $statusCode = 200;
        }
        catch (ProductNotFoundException $pe) {
            $message    = 'Error: ' . $pe->getMessage();
            $statusCode = 404;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message'    => $message,
            'statusCode' => $statusCode
        ];
    }

    public function eliminar(int $id): array {
        try {
            $result = $this->repository->delete($id);

            if (!$result) {
                throw new ProductNotFoundException("Product is not found with id " . $id);
            }

            $message    = "Product has been successfully deleted";
            $statusCode = 200;
        }
        catch (ProductNotFoundException $pe) {
            $message    = 'Error: ' . $pe->getMessage();
            $statusCode = 404;
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message'    => $message,
            'statusCode' => $statusCode
        ];
    }

}
