<?php

namespace App\Negocio\Usuario;

use App\Exceptions\UserNotFoundException;
use App\Models\UsuarioRepository;
use Exception;

class UserFinder {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    public function getAll(): array {
        try {
            $usuarios = $this->repository->getAll();
            $statusCode = empty($usuarios) ? 502 : 200;
        } catch (Exception $e){
            $usuarios = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'usuarios' => $usuarios,
            'statusCode' => $statusCode
        ];
    }

    public function getById($id): array {
        try{
            $usuario = $this->repository->getById($id);
            $statusCode = 200;
        }
        catch (UserNotFoundException $me) {
            $usuario = array('Error' => $me->getMessage());
            $statusCode = 404;
        }
        catch (Exception $e){
            $usuario = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'usuario' => $usuario,
            'statusCode' => $statusCode
        ];
    }

    public function checkRegisteredUser(string $username, string $password): array {
        //hashing password
        try{
            $usuario = $this->repository->validate($username, $password);
            $statusCode = 200;
        }
        catch (UserNotFoundException $me) {
            $usuario = array('Error' => $me->getMessage());
            $statusCode = 404;
        }
        catch (Exception $e){
            $usuario = array('Error' => $e->getMessage());
            $statusCode = 500;
        }

        return [
            'usuario' => $usuario,
            'statusCode' => $statusCode
        ];
    }

}
