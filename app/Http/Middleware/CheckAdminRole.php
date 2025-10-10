<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Verificamos si el usuario ha iniciado sesión Y si su id_tipo_usuario es 1 (Administrador)
        if (Auth::check() && Auth::user()->id_tipo_usuario == 1) {
            // 2. Si es Admin, lo dejamos pasar a la siguiente petición.
            return $next($request);
        }

        // 3. Si no es Admin, lo redirigimos a su dashboard principal.
        return redirect('/dashboard');
    }
}