<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class RoleHelper
{
    /**
     * Retorna el usuario autenticado o null.
     */
    protected static function user()
    {
        return Auth::user();
    }

    /**
     * Verifica si hay usuario autenticado.
     */
    public static function isAuthenticated(): bool
    {
        return Auth::check();
    }

    /**
     * SUPERADMIN
     */
    public static function isSuperAdmin(): bool
    {
        return self::isAuthenticated()
            && self::user()->hasRole('superadmin');
    }

    /**
     * AUXILIAR ADMINISTRATIVO DEL LABORATORIO
     */
    public static function isAuxAdmin(): bool
    {
        return self::isAuthenticated()
            && self::user()->hasRole('aux_admin');
    }

    /**
     * DOCENTE
     */
    public static function isDocente(): bool
    {
        return self::isAuthenticated()
            && self::user()->hasRole('docente');
    }

    /**
     * ESTUDIANTE
     */
    public static function isEstudiante(): bool
    {
        return self::isAuthenticated()
            && self::user()->hasRole('estudiante');
    }

    /**
     * Verifica si el usuario tiene alguno de los roles dados.
     * Acepta string o array.
     */
    public static function hasAnyRole(string|array $roles): bool
    {
        if (!self::isAuthenticated()) {
            return false;
        }

        $roles = is_array($roles) ? $roles : [$roles];

        if (self::isSuperAdmin()) {
            return true; // superadmin manda sobre todo
        }

        return self::user()->hasAnyRole($roles);
    }

    /**
     * ¿Puede gestionar inventario? (equipos, materiales)
     * Regla base: superadmin + auxiliar del laboratorio.
     */
    public static function canManageInventory(): bool
    {
        return self::hasAnyRole(['aux_admin', 'superadmin']);
    }

    /**
     * ¿Puede gestionar préstamos y reservas?
     * Regla propuesta: superadmin + auxiliar + docente.
     */
    public static function canManageLoansAndReservations(): bool
    {
        return self::hasAnyRole(['superadmin', 'aux_admin', 'docente']);
    }

    /**
     * ¿Es un usuario "operativo" del laboratorio? (no estudiante)
     */
    public static function isLabStaff(): bool
    {
        return self::hasAnyRole(['superadmin', 'aux_admin', 'docente']);
    }

    /**
     * Retorna el rol principal (primer rol asignado) o null.
     */
    public static function getMainRole(): ?string
    {
        if (!self::isAuthenticated()) {
            return null;
        }

        $role = self::user()->roles->first();
        return $role?->name;
    }

    /**
     * Retorna una etiqueta legible para mostrar en el panel:
     * "Superadministrador del Sistema", "Auxiliar Administrativo del Laboratorio", etc.
     */
    public static function getRoleLabel(?string $roleName = null): ?string
    {
        $roleName = $roleName ?? self::getMainRole();

        if (!$roleName) {
            return null;
        }

        $labels = [
            'superadmin' => 'Superadministrador del Sistema',
            'aux_admin'  => 'Auxiliar Administrativo del Laboratorio',
            'docente'    => 'Docente',
            'estudiante' => 'Estudiante',
        ];

        return $labels[$roleName] ?? ucfirst($roleName);
    }

    /**
     * Lista de roles soportados en Multilab.
     */
    public static function getAllRoles(): array
    {
        return [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ];
    }
}
