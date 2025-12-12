<?php

namespace App\Http\Middleware;

use App\Helpers\RoleHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAreaAccess
{
    /**
     * Middleware genérico para verificar acceso por ROL.
     *
     * Uso en rutas (ejemplos):
     *   ->middleware('check.area:docente')
     *   ->middleware('check.area:aux_admin,docente')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        // 1. Sin usuario autenticado
        if (! $user) {
            return $this->deny($request, 'Debes iniciar sesión para acceder a esta sección.');
        }

        // 2. SuperAdmin tiene acceso a todo
        if (RoleHelper::isSuperAdmin()) {
            return $next($request);
        }

        // 3. Si la ruta no definió roles, es error de config
        if (empty($roles)) {
            return $this->deny($request, 'El acceso a esta sección no está correctamente configurado.');
        }

        // 4. Verificar si el usuario tiene al menos uno de los roles permitidos
        foreach ($roles as $roleName) {
            $roleName = trim($roleName);

            if ($roleName !== '' && $user->hasRole($roleName)) {
                return $next($request);
            }
        }

        // 5. No tiene ningún rol permitido
        return $this->deny($request, 'No tienes acceso a esta sección.');
    }

    /**
     * Respuesta estándar cuando se deniega el acceso.
     * - Si es petición AJAX/JSON → 403 JSON
     * - Si es navegación normal → abort(403) con mensaje
     */
    protected function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ], 403);
        }

        abort(403, $message);
    }
}