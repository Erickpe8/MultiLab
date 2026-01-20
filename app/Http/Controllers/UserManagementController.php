<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $requestedView = $request->get('view', 'active');
        $allowedViews = ['active', 'pending', 'blocked'];
        $view = in_array($requestedView, $allowedViews, true) ? $requestedView : 'active';

        $pendingSearch    = $request->get('pending_search', '');
        $activeSearch     = $request->get('active_search', '');
        $activeRoleFilter = $request->get('active_role', '');
        $blockedSearch    = $request->get('blocked_search', '');
        $blockedRoleFilter = $request->get('blocked_role', '');
        $pendingPerPage   = $request->get('pending_per_page', 10);
        $activePerPage    = $request->get('active_per_page', 10);
        $blockedPerPage   = $request->get('blocked_per_page', 10);

        $pendingUsers = collect();

        if ($view === 'pending') {
            $pendingQuery = User::where('is_active', false)
                ->where('is_blocked', false);

            if ($pendingSearch !== '') {
                $pendingQuery->where(function ($q) use ($pendingSearch) {
                    $q->where(DB::raw("CONCAT_WS(' ', first_name, middle_name, first_surname, second_surname)"), 'like', "%{$pendingSearch}%")
                        ->orWhere('email', 'like', "%{$pendingSearch}%");
                });
            }

            $pendingUsers = $pendingQuery
                ->orderBy('created_at', 'desc')
                ->paginate($pendingPerPage, ['*'], 'pending_page')
                ->withQueryString();
        }

        $activeUsers = collect();

        if ($view === 'active') {
            $activeQuery = User::where('is_active', true)
                ->where('is_blocked', false)
                ->with('roles');

            if ($activeSearch !== '') {
                $activeQuery->where(function ($q) use ($activeSearch) {
                    $q->where(DB::raw("CONCAT_WS(' ', first_name, middle_name, first_surname, second_surname)"), 'like', "%{$activeSearch}%")
                        ->orWhere('email', 'like', "%{$activeSearch}%");
                });
            }

            if ($activeRoleFilter !== '') {
                $activeQuery->whereHas('roles', function ($q) use ($activeRoleFilter) {
                    $q->where('name', $activeRoleFilter);
                });
            }

            $activeUsers = $activeQuery
                ->orderBy('created_at', 'desc')
                ->paginate($activePerPage, ['*'], 'active_page')
                ->withQueryString();
        }

        $blockedUsers = collect();

        if ($view === 'blocked') {
            $blockedQuery = User::where('is_blocked', true)->with('roles');

            if ($blockedSearch !== '') {
                $blockedQuery->where(function ($q) use ($blockedSearch) {
                    $q->where(DB::raw("CONCAT_WS(' ', first_name, middle_name, first_surname, second_surname)"), 'like', "%{$blockedSearch}%")
                        ->orWhere('email', 'like', "%{$blockedSearch}%");
                });
            }

            if ($blockedRoleFilter !== '') {
                $blockedQuery->whereHas('roles', function ($q) use ($blockedRoleFilter) {
                    $q->where('name', $blockedRoleFilter);
                });
            }

            $blockedUsers = $blockedQuery
                ->orderBy('created_at', 'desc')
                ->paginate($blockedPerPage, ['*'], 'blocked_page')
                ->withQueryString();
        }

        $roles = Role::whereIn('name', [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ])->get();

        return view('usermanagement.management', compact(
            'view',
            'pendingUsers',
            'activeUsers',
            'blockedUsers',
            'roles',
            'pendingSearch',
            'activeSearch',
            'blockedSearch',
            'activeRoleFilter',
            'blockedPerPage',
            'blockedRoleFilter'
        ));
    }

    public function approve(Request $request, User $user)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
                Log::warning('Intento de acceso no autorizado a approve', [
                    'user_id'        => auth()->id(),
                    'target_user_id' => $user->id,
                ]);
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $validated = $request->validate([
                'role' => 'required|exists:roles,name',
                'area' => 'nullable|string|max:200',
            ]);

            Log::info('Aprobando usuario', [
                'user_id'       => $user->id,
                'user_email'    => $user->email,
                'role'          => $validated['role'],
                'before_active' => $user->is_active,
            ]);

            DB::beginTransaction();

            $before = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $user->is_active = true;
            $user->is_blocked = false;
            $user->area = $validated['area'] ?? null;
            $user->role_name = $validated['role'];
            $user->save();

            $user->syncRoles([$validated['role']]);
            $user->refresh()->load('roles');

            $after = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $this->recordAudit($user, 'update', $before, $after);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Usuario {$user->name} aprobado correctamente. Ya puede iniciar sesión.",
                'user'    => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'email'     => $user->email,
                    'is_active' => $user->is_active,
                    'roles'     => $user->roles->pluck('name'),
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            Log::error('Error de validación al aprobar usuario', [
                'errors'  => $e->errors(),
                'user_id' => $user->id,
            ]);

            return response()->json([
                'error'   => 'Datos inválidos',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al aprobar usuario', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al aprobar usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function reject(User $user)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $userName  = $user->name;
            $userEmail = $user->email;

            Log::info('Rechazando usuario', [
                'user_id'    => $user->id,
                'user_email' => $userEmail,
                'rejected_by'=> auth()->id(),
            ]);

            $before = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $this->recordAudit($user, 'delete', $before, []);

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Solicitud de {$userName} rechazada y eliminada correctamente.",
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al rechazar usuario', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al rechazar usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function updateRole(Request $request, User $user)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            $validated = $request->validate([
                'role' => 'required|exists:roles,name',
                'area' => 'nullable|string|max:200',
            ]);

            Log::info('Actualizando usuario', [
                'user_id'   => $user->id,
                'old_roles' => $user->roles->pluck('name')->toArray(),
                'new_role'  => $validated['role'],
            ]);

            DB::beginTransaction();

            $before = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $user->role_name = $validated['role'];
            $user->area = $validated['area'] ?? null;
            $user->save();

            $user->syncRoles([$validated['role']]);
            $user->refresh()->load('roles');

            DB::commit();

            $after = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $this->recordAudit($user, 'update', $before, $after);

            return response()->json([
                'success' => true,
                'message' => "Usuario {$user->name} actualizado correctamente.",
                'user'    => [
                    'id'        => $user->id,
                    'name'      => $user->name,
                    'is_active' => $user->is_active,
                    'roles'     => $user->roles->pluck('name'),
                ],
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            return response()->json([
                'error'   => 'Datos inválidos',
                'details' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar usuario', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al actualizar usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deactivate(User $user)
    {
        return $this->block($user);
    }

    public function block(User $user)
    {
        return $this->toggleBlockState($user, true);
    }

    public function unblock(User $user)
    {
        return $this->toggleBlockState($user, false);
    }

    public function destroy(User $user)
    {
        try {
            if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            if ($user->id === auth()->id()) {
                return response()->json([
                    'error' => 'No puedes eliminarte a ti mismo.',
                ], 400);
            }

            $userName = $user->name;

            Log::warning('Eliminando usuario permanentemente', [
                'user_id'    => $user->id,
                'user_email' => $user->email,
                'deleted_by' => auth()->id(),
            ]);

            $before = [
                'is_active' => $user->is_active,
                'is_blocked' => $user->is_blocked,
                'role_name' => $user->role_name,
                'area' => $user->area,
            ];

            $this->recordAudit($user, 'delete', $before, []);

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => "Usuario {$userName} eliminado permanentemente.",
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al eliminar usuario', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al eliminar usuario: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        $user = User::where('id', $id)
            ->where('is_active', true)
            ->with('roles')
            ->firstOrFail();

        return view('usermanagement.partials.show-user', compact('user'));
    }

    /**
     * Toggle the blocked flag for a user.
     */
    private function toggleBlockState(User $user, bool $block)
    {
        if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
            return response()->json(['error' => 'No autorizado'], 403);
        }

        if ($block && ($user->is_blocked || ! $user->is_active)) {
            return response()->json(['error' => 'El usuario no está en un estado válido para bloquearse.'], 400);
        }

        if (! $block && ! $user->is_blocked) {
            return response()->json(['error' => 'El usuario no está bloqueado actualmente.'], 400);
        }

        $before = [
            'is_active' => $user->is_active,
            'is_blocked' => $user->is_blocked,
            'role_name' => $user->role_name,
            'area' => $user->area,
        ];

        $user->is_blocked = $block;
        $user->is_active = ! $block;
        $user->save();

        $after = [
            'is_active' => $user->is_active,
            'is_blocked' => $user->is_blocked,
            'role_name' => $user->role_name,
            'area' => $user->area,
        ];

        $this->recordAudit($user, 'update', $before, $after);

        $actionVerb = $block ? 'bloqueado' : 'desbloqueado';

        Log::info(ucfirst($actionVerb) . ' usuario', [
            'user_id'    => $user->id,
            'user_email' => $user->email,
            'blocked_by' => auth()->id(),
            'is_blocked' => $user->is_blocked,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Usuario {$user->name} {$actionVerb} correctamente.",
            'user'    => [
                'id'        => $user->id,
                'is_blocked' => $user->is_blocked,
            ],
        ], 200);
    }

    /**
     * Auditoría simple para cambios críticos.
     */
    private function recordAudit(User $user, string $action, array $before = [], array $after = []): void
    {
        AuditLog::create([
            'table_name'  => 'users',
            'row_pk'      => (string) $user->id,
            'action'      => $action,
            'before_json' => $before ?: null,
            'after_json'  => $after ?: null,
            'user_id'     => auth()->id(),
            'ip'          => request()->ip(),
            'user_agent'  => request()->userAgent(),
        ]);
    }
}
