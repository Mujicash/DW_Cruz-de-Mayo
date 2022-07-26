<?php

namespace App\Models;

use App\Exceptions\UserNotFoundException;

interface UsuarioRepository {

    public function create(Usuario $usuario): bool;

    public function validate(string $usuario, string $password): ?Usuario;

    /**
     * @param int $idUsuario
     * @return Usuario|null
     * @throws UserNotFoundException
     */
    public function getById(int $idUsuario): ?Usuario;

    public function getAll(): array;

    public function update(Usuario $usuario): int;

    public function delete(int $idUsuario): int;
}
