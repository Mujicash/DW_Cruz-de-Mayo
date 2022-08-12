<?php

namespace App\Models;

interface TipoUsuarioRepository {

    public function getIdByName(string $nombre): int;

}
