<?php

namespace App\Negocio\Usuario;

use App\Models\UsuarioRepository;
use Exception;

class DeleteUser {

    private UsuarioRepository $repository;

    /**
     * @param UsuarioRepository $repository
     */
    public function __construct(UsuarioRepository $repository) {
        $this->repository = $repository;
    }

    public function __invoke(int $id): array {

        try {
            $result = $this->repository->delete($id);

            if ($result == 1) {
                $message    = 'User has been successfully deleted';
                $statusCode = 200;
            }
            else {
                $message    = "User is not found with id " . $id;
                $statusCode = 404;
            }
        }
        catch (Exception $e) {
            $message    = 'Error: ' . $e->getMessage();
            $statusCode = 500;
        }

        return [
            'message'    => $message,
            'statusCode' => $statusCode
        ];
    }

}
