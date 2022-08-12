<?php

namespace App\Models;

interface AutenticacionRepository {

    public function checkToken(int $id, string $token): bool;

    public function createToken(int $id, string $token);
}
