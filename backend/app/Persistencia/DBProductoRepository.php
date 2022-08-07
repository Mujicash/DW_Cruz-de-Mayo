<?php

namespace App\Persistencia;

use App\Exceptions\ProductNotFoundException;
use App\Models\Producto;
use App\Models\ProductoRepository;
use Illuminate\Support\Facades\DB;

class DBProductoRepository implements ProductoRepository {

    public function create(Producto $producto): bool {
        return DB::insert('INSERT into productos (nombre, laboratorio, precio_venta, descripcion, id_unidad)
            VALUES (:nombre, :laboratorio, :precio_venta, :descripcion, :id_unidad)', [
            'nombre'       => $producto->getNombre(),
            'laboratorio'  => $producto->getLaboratorio(),
            'precio_venta' => $producto->getPrecioVenta(),
            'descripcion'  => $producto->getDescripcion(),
            'id_unidad'    => $producto->getIdUnidad()
        ]);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getByName(string $nombre): array {
        $result = DB::select('SELECT * FROM productos WHERE nombre like :nombre', ['nombre' => $nombre . '%']);
        $productos = array();

        if (empty($result)) {
            throw new ProductNotFoundException('There are no products with the name ' . $nombre, 404);
        }

        foreach ($result as $i) {
            $producto    = new Producto($i->id, $i->nombre, $i->laboratorio, $i->precio_venta, $i->descripcion,
                                        $i->id_unidad);
            $productos[] = $producto;
        }

        return $productos;
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getById(int $id): ?Producto {
        $result = DB::select('SELECT * FROM productos WHERE id = :id', ['id' => $id]);

        if (empty($result)) {
            throw new ProductNotFoundException('The product with the id ' . $id . ' was not found.');
        }

        return new Producto($result[0]->id, $result[0]->nombre, $result[0]->laboratorio, $result[0]->precio_venta,
                            $result[0]->descripcion, $result[0]->id_unidad);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getAll(): array {
        $result    = DB::select('SELECT * FROM productos');
        $productos = array();

        if (empty($result)) {
            throw new ProductNotFoundException('No product was found', 404);
        }

        foreach ($result as $i) {
            $producto    = new Producto($i->id, $i->nombre, $i->laboratorio, $i->precio_venta, $i->descripcion,
                                        $i->id_unidad);
            $productos[] = $producto;
        }

        return $productos;
    }

    public function update(Producto $producto): bool {
        return DB::update('UPDATE productos
            set nombre = :nombre, laboratorio = :laboratorio, precio_venta = :precio_venta, descripcion = :descripcion, id_unidad = :id_unidad
            WHERE id = :id', [
            'nombre'       => $producto->getNombre(),
            'laboratorio'  => $producto->getLaboratorio(),
            'precio_venta' => $producto->getPrecioVenta(),
            'descripcion'  => $producto->getDescripcion(),
            'id_unidad'    => $producto->getIdUnidad(),
            'id'           => $producto->getId()
        ]);
    }

    public function delete(int $id): bool {
        return DB::delete('DELETE FROM productos WHERE id = :id', ['id' => $id]);
    }

    /**
     * @throws ProductNotFoundException
     */
    public function getId(string $nombre) {
        $result = DB::select('select id from productos where nombre = :nombre', ['nombre' => $nombre]);

        if (empty($result)) {
            throw new ProductNotFoundException('No product was found', 404);
        }

        return $result[0]->id;
    }
}
