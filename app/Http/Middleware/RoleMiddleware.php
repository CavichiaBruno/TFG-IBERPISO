<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware que controla el acceso según el rol y el estado del usuario.
 *
 * Se ejecuta antes de entrar a las rutas protegidas (como las del panel admin).
 * Verifica tres condiciones en orden:
 * 1. Que el usuario esté autenticado (tiene sesión iniciada).
 * 2. Que su rol coincida con alguno de los roles permitidos para esa ruta.
 * 3. Que su cuenta esté activa (no desactivada por un admin).
 */
class RoleMiddleware
{
    /**
     * Gestiona la solicitud entrante comprobando rol y estado del usuario.
     *
     * Si no supera alguna comprobación, redirige al login o devuelve un error 403.
     *
     * @param  \Illuminate\Http\Request $request  La solicitud HTTP entrante
     * @param  \Closure                 $next     La siguiente capa del middleware
     * @param  string                   ...$roles Roles permitidos para acceder a la ruta
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        if (!in_array($request->user()->rol, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }

        if (!$request->user()->activo) {
            auth()->logout();
            return redirect()->route('login')->withErrors(['email' => 'Tu cuenta está desactivada.']);
        }

        return $next($request);
    }
}
