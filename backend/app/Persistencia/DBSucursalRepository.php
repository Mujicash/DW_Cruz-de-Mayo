<?php

namespace App\Persistencia;

use App\Models\Entidades\Sucursal;
use App\Models\Repositorios\SucursalRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DBSucursalRepository implements SucursalRepository {

    public function create(Sucursal $sucursal): bool {
        return DB::insert('INSERT INTO sucursales (nombre, direccion) VALUES (:nombre, :direccion)', [
            'nombre'    => $sucursal->getNombre(),
            'direccion' => $sucursal->getDireccion()
        ]);
    }

    public function getById(int $idSucursal): ?Sucursal {
        $result   = DB::select('SELECT * FROM sucursales WHERE id = :id', ['id' => $idSucursal]);
        $sucursal = null;

        if (!empty($result)) {
            $sucursal = new Sucursal($result[0]->id, $result[0]->nombre, $result[0]->direccion);
        }

        return $sucursal;
    }

    /**
     * @throws Exception
     */
    public function getAll(): array {
        $result     = DB::select('SELECT * FROM sucursales');
        $sucursales = array();

        if (empty($result)) {
            throw new Exception('No se encontro ninguna sucursal', 204);
        }

        foreach ($result as $i) {
            $sucursal     = new Sucursal($i->id, $i->nombre, $i->direccion);
            $sucursales[] = $sucursal;
        }

        return $sucursales;
    }

    public function update(Sucursal $sucursal): int {
        return DB::update('UPDATE sucursales set nombre = :nombre, direccion = :direccion WHERE id = :id', [
            'nombre'    => $sucursal->getNombre(),
            'direccion' => $sucursal->getDireccion(),
            'id'        => $sucursal->getId()
        ]);
    }

    public function delete(int $idSucursal): int {
        return DB::delete('DELETE FROM sucursales WHERE id = :id', ['id' => $idSucursal]);
    }

    public function getIdByName(string $nombre): int {
        $result = DB::select('SELECT id FROM sucursales WHERE nombre = :nombre', ['nombre' => $nombre]);

        return $result[0]->id;
    }
}
