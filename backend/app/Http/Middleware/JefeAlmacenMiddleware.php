<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JefeAlmacenMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next) {

        if (!isset($request['tipo']) || $request['tipo'] !== 'Jefe Almacen') {
            return response(['success' => false, 'message' => 'No tienes privilegios de Jefe de Almacen.'], 401);
        }

        return $next($request);
    }
}
