<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware {

    public function __construct() {}

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {
        //dd($request['id_tipo']);
        if (!isset($request['tipo']) || $request['tipo'] !== 'Administrador') {
            return response(['success' => false, 'message' => 'No tienes privilegios de Administriador.'], 401);
        }

        return $next($request);
    }
}
