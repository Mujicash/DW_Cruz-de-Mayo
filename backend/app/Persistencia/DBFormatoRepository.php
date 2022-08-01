<?php

namespace App\Persistencia;

use App\Exceptions\FormatNotFoundException;
use App\Models\Formato;
use App\Models\FormatoRepository;
use Illuminate\Support\Facades\DB;

class DBFormatoRepository implements FormatoRepository {

    public function create(Formato $formato): bool {
        return DB::insert('INSERT into formatos (formato) VALUES (:nombre)', ['nombre' => $formato->getNombre()]);
    }

    /**
     * @throws FormatNotFoundException
     */
    public function getById(int $id): ?Formato {
        $result = DB::select('SELECT * FROM formatos WHERE id = :id', ['id' => $id]);

        if (empty($result)) {
            throw new FormatNotFoundException('The format with the id ' . $id . ' was not found.');
        }

        return new Formato($result[0]->id, $result[0]->formato);
    }

    /**
     * @throws FormatNotFoundException
     */
    public function getByName(string $name): ?Formato {
        $result = DB::select('SELECT * FROM formatos WHERE formato = :nombre', ['nombre' => $name]);

        if (empty($result)) {
            throw new FormatNotFoundException('The format with the name ' . $name . ' was not found.');
        }

        return new Formato($result[0]->id, $result[0]->formato);
    }

    /**
     * @throws FormatNotFoundException
     */
    public function getAll(): array {
        $result   = DB::select('SELECT * FROM formatos');
        $formatos = array();

        if (empty($result)) {
            throw new FormatNotFoundException('No format was found');
        }

        foreach ($result as $i) {
            $formato    = new Formato($i->id, $i->formato);
            $formatos[] = $formato;
        }

        return $formatos;
    }
}
