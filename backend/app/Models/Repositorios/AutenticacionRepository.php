<?php

namespace App\Models\Repositorios;

interface AutenticacionRepository {

    public function checkToken(int $id, string $token): bool;

    public function createToken(int $id, string $token);
}
