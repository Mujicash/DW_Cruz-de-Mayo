<?php

namespace App\Persistencia;


use App\Models\Usuario;
use App\Models\UsuarioDTO;
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
    public function validate(string $usuario, string $password): UsuarioDTO {
        $result = DB::select("select u.id, concat(u.nombre, ' ', u.apellido_pat, ' ', u.apellido_mat) Nombre_completo, t.tipo, s.nombre, s.direccion
            from usuarios u inner join sucursales s on u.id_sucursal = s.id inner join tipo_usuarios t on u.id_tipo = t.id
            where u.usuario = :usuario and u.pasword = :password", [
            'usuario'  => $usuario,
            'password' => $password
        ]);

        if (empty($result)) {
            throw new UserNotFoundException('User is not found with this credentials.', 204);
        }

        return new UsuarioDTO($result[0]->id, $result[0]->Nombre_completo, $usuario, $result[0]->tipo,
                              $result[0]->nombre,
                              $result[0]->direccion);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getById(int $idUsuario): ?Usuario {
        $result = DB::select('SELECT * FROM usuarios WHERE id = :id', ['id' => $idUsuario]);

        if (empty($result)) {
            throw new UserNotFoundException('User is not found by id ' . $idUsuario, 204);
        }
        return new Usuario($result[0]->id, $result[0]->usuario, $result[0]->nombre,
                           $result[0]->apellido_pat, $result[0]->apellido_mat,
                           $result[0]->pasword, $result[0]->id_tipo, $result[0]->id_sucursal);
    }

    /**
     * @throws UserNotFoundException
     */
    public function getAll(): array {
        $result   = DB::select("select u.id, concat(u.nombre, ' ', u.apellido_pat, ' ', u.apellido_mat) as nombre, u.usuario, t.tipo, s.nombre sucursal
                    from usuarios u inner join sucursales s on u.id_sucursal = s.id inner join tipo_usuarios t on u.id_tipo = t.id");
        $usuarios = array();

        if (empty($result)) {
            throw new UserNotFoundException('No user was found', 204);
        }

        foreach ($result as $i) {
            $usuario    = new UsuarioDTO($i->id, $i->nombre, $i->usuario, $i->tipo, $i->sucursal);
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
