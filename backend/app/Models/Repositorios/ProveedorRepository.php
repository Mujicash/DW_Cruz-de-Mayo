<?php

namespace App\Models\Repositorios;

use App\Models\Entidades\Proveedor;

interface ProveedorRepository {

    public function create(Proveedor $proveedor);

    public function getById(int $id);

    public function update(Proveedor $proveedor);

    public function delete(int $id);

    public function getAll();

    public function getId(string $proveedor);
}
