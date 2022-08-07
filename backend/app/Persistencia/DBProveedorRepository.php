<?php

namespace App\Persistencia;

use App\Exceptions\SupplierNotFoundException;
use App\Models\Proveedor;
use App\Models\ProveedorRepository;
use Illuminate\Support\Facades\DB;

class DBProveedorRepository implements ProveedorRepository {

    public function create(Proveedor $proveedor): bool {
        return DB::insert('INSERT INTO proveedores (nombre, ruc, telefono, direccion, correo)
            VALUES (:nombre, :ruc, :telefono, :direccion, :correo)', [
            'nombre'    => $proveedor->getNombre(),
            'ruc'       => $proveedor->getRuc(),
            'telefono'  => $proveedor->getTelefono(),
            'direccion' => $proveedor->getDireccion(),
            'correo'    => $proveedor->getCorreo()
        ]);
    }

    /**
     * @throws SupplierNotFoundException
     */
    public function getByName(string $nombre): array {
        $result      = DB::select('SELECT * FROM proveedores WHERE nombre like :nombre', ['nombre' => $nombre . '%']);
        $proveedores = array();

        if (empty($result)) {
            throw new SupplierNotFoundException('There are not suppliers with name ' . $nombre, 404);
        }

        foreach ($result as $i) {
            $proveedor     = new Proveedor($i->nombre, $i->ruc, $i->direccion, $i->correo, $i->telefono, $i->id);
            $proveedores[] = $proveedor;
        }

        return $proveedores;
    }

    public function update(Proveedor $proveedor): int {
        return DB::update('UPDATE proveedores
            set nombre = :nombre, ruc = :ruc, direccion = :direccion, correo = :correo, telefono = :telefono
            WHERE id = :id', [
            'nombre'    => $proveedor->getNombre(),
            'ruc'       => $proveedor->getRuc(),
            'telefono'  => $proveedor->getTelefono(),
            'direccion' => $proveedor->getDireccion(),
            'correo'    => $proveedor->getCorreo(),
            'id'        => $proveedor->getId()
        ]);
    }

    public function delete(int $id): int {
        return DB::delete('DELETE FROM proveedores WHERE id = :id', ['id' => $id]);
    }

    /**
     * @throws SupplierNotFoundException
     */
    public function getAll(): array {
        $result      = DB::select('SELECT * FROM proveedores');
        $proveedores = array();

        if (empty($result)) {
            throw new SupplierNotFoundException('No suppliers was found.', 404);
        }

        foreach ($result as $i) {
            $proveedor     = new Proveedor($i->nombre, $i->ruc, $i->direccion, $i->correo, $i->telefono, $i->id);
            $proveedores[] = $proveedor;
        }

        return $proveedores;
    }

    /**
     * @throws SupplierNotFoundException
     */
    public function getId(string $proveedor) {
        $result = DB::select('SELECT id FROM proveedores WHERE nombre = :nombre', ['nombre' => $proveedor]);

        if (empty($result)) {
            throw new SupplierNotFoundException('No suppliers was found.', 404);
        }

        return $result[0]->id;
    }
}
