<?php

namespace App\Models;

interface ProveedorRepository {

    public function create(Proveedor $proveedor);

    public function getByName(string $nombre);

    public function update(Proveedor $proveedor);

    public function delete(int $id);

    public function getAll();

    public function getId(string $proveedor);
}
