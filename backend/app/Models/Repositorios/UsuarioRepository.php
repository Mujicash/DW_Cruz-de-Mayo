<?php

namespace App\Models\Repositorios;

use App\Models\DTOs\UsuarioDTO;
use App\Models\Entidades\Usuario;

interface UsuarioRepository {

    public function create(Usuario $usuario): bool;

    public function validate(string $usuario, string $password): UsuarioDTO;

    public function getById(int $idUsuario): ?Usuario;

    public function getAll(): array;

    public function update(Usuario $usuario): int;

    public function delete(int $idUsuario): int;

    public function getBranchIdByUserId(int $userId): int;
}
