<?php

namespace App\Models;

interface AutenticacionRepository {

    public function checkToken(string $token): bool;

    public function createToken(int $id, string $token);
}
