<?php

namespace App\Models\Repositorios;

interface TipoUsuarioRepository {

    public function getIdByName(string $nombre): int;

}
