<?php

namespace App\Persistencia;

use App\Models\Repositorios\AutenticacionRepository;
use Exception;
use Illuminate\Support\Facades\DB;

class DBAutenticacionRepository implements AutenticacionRepository {

    public function checkToken(int $id, string $token): bool {
        $result = DB::select('select count(*) logeado from usuarios where id = :id and api_token = :token', ['id' => $id, 'token' => $token]);

        return ($result[0]->logeado == 1);
    }

    /**
     * @throws Exception
     */
    public function createToken(int $id, string $token): int {
        $result = DB::update('update usuarios set api_token = :token where id = :id', [
            'token' => $token,
            'id'    => $id
        ]);

        if ($result == 0) {
            throw new Exception('Error al crear el toke', 500);
        }

        return $result;
    }
}
