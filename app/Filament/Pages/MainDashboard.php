<?php

namespace App\Filament\Pages;

use App\Helpers\RoleHelper;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Route;

class MainDashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.main-dashboard';

    protected static ?string $title = 'Dahsboard Principal';

    public function getSections(): array
    {
        $sections = [
            [
                'tag' => 'Administración',
                'title' => 'Control y vigilancia',
                'subtitle' => 'Supervisa accesos, permisos y solicitudes críticas.',
                'cards' => [
                    [
                        'title' => 'Usuarios Activos',
                        'description' => 'Usuarios con acceso vigente y roles asignados.',
                        'icon' => 'heroicon-o-user-group',
                        'route' => 'user-management.index',
                        'params' => ['view' => 'active'],
                        'badge' => 'Usuarios',
                        'cta' => 'Ver usuarios',
                        'allowed_roles' => ['superadmin', 'aux_admin'],
                    ],
                    [
                        'title' => 'Solicitudes Pendientes',
                        'description' => 'Registros nuevos en revisión por el equipo de vigilancia.',
                        'icon' => 'heroicon-o-clock',
                        'route' => 'user-management.pending',
                        'badge' => 'Flujos',
                        'cta' => 'Revisar pendientes',
                        'allowed_roles' => ['superadmin', 'aux_admin'],
                    ],
                    [
                        'title' => 'Usuarios Bloqueados',
                        'description' => 'Cuentas suspendidas temporalmente por control de seguridad.',
                        'icon' => 'heroicon-o-user-minus',
                        'route' => 'user-management.blocked',
                        'badge' => 'Seguridad',
                        'cta' => 'Ver bloqueos',
                        'allowed_roles' => ['superadmin', 'aux_admin'],
                    ],
                ],
            ],
            [
                'tag' => 'Módulos',
                'title' => 'Accesos clave',
                'subtitle' => 'Navega rápido a los módulos que gestionan préstamos y aulas.',
                'cards' => [
                    [
                        'title' => 'Préstamos',
                        'description' => 'Administra solicitudes, entregas y devoluciones de equipos.',
                        'icon' => 'heroicon-o-credit-card',
                        'route' => 'filament.dashboard.resources.loans.index',
                        'badge' => 'Operaciones',
                        'cta' => 'Abrir módulo',
                        'allowed_roles' => ['superadmin', 'aux_admin', 'docente', 'estudiante'],
                    ],
                    [
                        'title' => 'Aula B202',
                        'description' => 'Consulta ocupación, reservas y disponibilidad del laboratorio.',
                        'icon' => 'heroicon-o-building-office',
                        'route' => 'filament.dashboard.resources.classroom-loans.index',
                        'badge' => 'Espacios',
                        'cta' => 'Ver aula B202',
                        'allowed_roles' => ['superadmin', 'aux_admin', 'docente'],
                    ],
                ],
            ],
        ];

        return collect($sections)->map(function ($section) {
            $section['visibleCards'] = collect($section['cards'])->filter(function ($card) {
                return isset($card['allowed_roles']) && RoleHelper::hasAnyRole($card['allowed_roles']);
            })->map(function ($card) {
                $routeName = $card['route'] ?? null;
                $routeParams = $card['params'] ?? [];
                $card['hasRoute'] = $routeName ? Route::has($routeName) : false;
                $card['href'] = $card['hasRoute'] ? route($routeName, $routeParams) : ($card['href'] ?? '#');

                return $card;
            });

            return $section;
        })->filter(fn($section) => $section['visibleCards']->isNotEmpty())->toArray();
    }
}
