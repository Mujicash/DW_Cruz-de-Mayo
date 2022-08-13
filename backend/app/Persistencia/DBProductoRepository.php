<?php

namespace App\Persistencia;

use App\Exceptions\ProductNotFoundException;
use App\Models\DTOs\ProductoDTO;
use App\Models\Entidades\Producto;
use App\Models\Repositorios\ProductoRepository;
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
            throw new ProductNotFoundException('There are no products with the name ' . $nombre, 204);
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
        $result    = DB::select('select p.id, p.nombre, p.laboratorio, f.formato from productos p inner join formatos f on p.id_unidad = f.id');
        $productos = array();

        if (empty($result)) {
            throw new ProductNotFoundException('No product was found', 204);
        }

        foreach ($result as $i) {
            $producto    = new ProductoDTO($i->id, $i->nombre, $i->formato, $i->laboratorio);
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
            throw new ProductNotFoundException('No product was found', 204);
        }

        return $result[0]->id;
    }
}
