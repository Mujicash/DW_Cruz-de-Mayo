<?php

namespace App\Http\Middleware;

use App\Negocio\AutenticacionLN;
use App\Persistencia\DBAutenticacionRepository;
use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var Auth
     */
    protected Auth $auth;

    /**
     * Create a new middleware instance.
     *
     * @param Auth $auth
     * @return void
     */
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string|null $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $guard = null) {

        if ($request->bearerToken() == null) {
            return response(['message' => 'Unauthorized.'], 401);
        }

        $autRepo = new DBAutenticacionRepository();
        $autenLN = new AutenticacionLN($autRepo);
        $token   = $request->bearerToken();
        $id      = $request['id_usuario'];

        if (!$autenLN->verificarToken($id, $token)) {
            return response(['message' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
