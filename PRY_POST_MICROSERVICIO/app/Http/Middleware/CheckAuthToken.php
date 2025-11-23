<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Obtener el token del header Authorization: Bearer XXXXX
        $token = $request->bearerToken();

        if (! $token) {
            return response()->json([
                'message' => 'Unauthorized, token invalido',
            ], 401);
        }

        // 2. Llamar al microservicio de autenticación para validar el token
        $response = Http::withToken($token)
            ->get('http://192.168.100.31:8000/api/validate-token');

        // 3. Si la petición falla (token inválido)
        if ($response->failed()) {
            return response()->json([
                'message' => 'Unauthorized, token invalido',
            ], 401);
        }

        // 4. Guardar los datos del usuario autenticado en la request
        $request->merge([
            'auth_user' => $response->json('user'),
        ]);

        // 5. Continuar con la petición
        return $next($request);
    }
}
