<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EncargadoBoticaMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!isset($request['id_tipo']) || $request['id_tipo'] !== 3) {
            return response(['success' => false, 'message' => 'No tienes privilegios de Encargado de Botica.'], 401);
        }

        return $next($request);
    }
}
