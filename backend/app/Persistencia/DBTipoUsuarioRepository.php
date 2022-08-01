<?php

namespace App\Persistencia;

use App\Models\TipoUsuarioRepository;
use Illuminate\Support\Facades\DB;

class DBTipoUsuarioRepository implements TipoUsuarioRepository {

    public function getIdByName(string $nombre): int {
        $result = DB::select('SELECT id FROM tipo_usuarios WHERE tipo = :tipo', ['tipo' => $nombre]);

        return $result[0]->id;
    }
}
