<?php

namespace App\Negocio;

use App\Exceptions\FormatNotFoundException;
use App\Models\Formato;
use App\Models\FormatoRepository;
use Exception;

class FormatoLN {

    private FormatoRepository $repository;

    /**
     * @param FormatoRepository $repository
     */
    public function __construct(FormatoRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @throws Exception
     */
    public function registrar(string $nombre) {

        $formato = new Formato(0, $nombre);
        $result  = $this->repository->create($formato);

        if (!$result) {
            throw new Exception('An error has occurred in the Format registration');
        }
    }

    /**
     * @param $id
     * @return Formato
     * @throws Exception
     */
    public function obtener($id): Formato {
        return $this->repository->getById($id);
    }

    /**
     * @throws Exception
     */
    public function obtenerTodos(): array {
        return $this->repository->getAll();
    }

    /**
     * @throws Exception
     */
    public function obtenerPorNombre(string $formato): ?Formato {
        return $this->repository->getByName($formato);
    }


}
