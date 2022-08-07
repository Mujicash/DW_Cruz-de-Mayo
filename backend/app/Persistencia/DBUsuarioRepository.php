<?php

namespace App\Persistencia;


use App\Models\Usuario;
use App\Models\UsuarioRepository;
use App\Exceptions\UserNotFoundException;
use Exception;
use Illuminate\Support\Facades\DB;

class DBUsuarioRepository implements UsuarioRepository {

    /**
     * @param Usuario $usuario
     * @return bool
     * @throws Exception
     */
    public function create(Usuario $usuario): bool {
        return DB::insert('INSERT INTO usuarios (usuario, nombre, apellido_pat, apellido_mat, pasword, fecha_creacion, ultima_conexion, id_tipo, id_sucursal)
            VALUES (:usuario, :nombre, :apellido_pat, :apellido_mat, :password, NOW(), NOW(), :tipo, :sucursal)', [
            'usuario'      => $usuario->getUsuario(),
            'nombre'       => $usuario->getNombre(),
            'apellido_pat' => $usuario->getApellidoPaterno(),
            'apellido_mat' => $usuario->getApellidoMaterno(),
            'password'     => $usuario->getPassword(),
            'tipo'         => $usuario->getTipo(),
            'sucursal'     => $usuario->getSucursal()
        ]);
    }

    /**
     * @throws UserNotFoundException
     */
    public function validate(string $usuario, string $password): ?Usuario {
        $result = DB::select('select * from usuarios where usuario = :usuario and pasword = :password', [
            'usuario'  => $usuario,
            'password' => $password
        ]);

        if (empty($result)) {
            throw new UserNotFoundException('User is not found with this credentials.', 404);
        }

        return new Usuario($result[0]->id, $result[0]->usuario, $result[0]->nombre,
                           $result[0]->apellido_pat, $result[0]->apellido_mat,
                           $result[0]->pasword, $result[0]->id_tipo, $result[0]->id_sucursal);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getById(int $idUsuario): ?Usuario {
        $result = DB::select('SELECT * FROM usuarios WHERE id = :id', ['id' => $idUsuario]);

        if (empty($result)) {
            throw new UserNotFoundException('User is not found by id ' . $idUsuario, 404);
        }
        return new Usuario($result[0]->id, $result[0]->usuario, $result[0]->nombre,
                           $result[0]->apellido_pat, $result[0]->apellido_mat,
                           $result[0]->pasword, $result[0]->id_tipo, $result[0]->id_sucursal);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getAll(): array {
        $result   = DB::select('SELECT * FROM usuarios');
        $usuarios = array();

        if (empty($result)) {
            throw new UserNotFoundException('No user was found', 404);
        }

        foreach ($result as $i) {
            $usuario    = new Usuario($i->id, $i->usuario, $i->nombre, $i->apellido_pat, $i->apellido_mat,
                                      $i->pasword, $i->id_tipo, $i->id_sucursal);
            $usuarios[] = $usuario;
        }

        return $usuarios;
    }

    public function update(Usuario $usuario): int {
        return DB::update('UPDATE usuarios
            set usuario = :usuario, nombre = :nombre, apellido_pat = :apellido_pat, apellido_mat = :apellido_mat,
            pasword = :password, id_tipo = :tipo, id_sucursal = :sucursal WHERE id = :id', [
            'usuario'      => $usuario->getUsuario(),
            'nombre'       => $usuario->getNombre(),
            'apellido_pat' => $usuario->getApellidoPaterno(),
            'apellido_mat' => $usuario->getApellidoMaterno(),
            'password'     => $usuario->getPassword(),
            'tipo'         => $usuario->getTipo(),
            'sucursal'     => $usuario->getSucursal(),
            'id'           => $usuario->getId()
        ]);
    }

    public function delete(int $idUsuario): int {
        return DB::delete('DELETE from usuarios WHERE id = :id', ['id' => $idUsuario]);
    }

    /**
     * @throws Exception
     */
    public function getBranchIdByUserId(int $userId): int {
        $branchId = DB::select('select id_sucursal from usuarios where id = :id', ['id' => $userId]);

        if (empty($branchId)) {
            throw new Exception('Error al encontrar la sucursal del usuario', 500);
        }

        return $branchId[0]->id_sucursal;
    }
}
