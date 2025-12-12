<?php

namespace App\Http\Controllers;

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

        $view = $request->get('view', 'pending');

        $pendingSearch    = $request->get('pending_search', '');
        $activeSearch     = $request->get('active_search', '');
        $activeRoleFilter = $request->get('active_role', '');
        $pendingPerPage   = $request->get('pending_per_page', 10);
        $activePerPage    = $request->get('active_per_page', 10);

        $pendingUsers = collect();

        if ($view === 'pending') {
            $pendingQuery = User::where('is_active', false);

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
            $activeQuery = User::where('is_active', true)->with('roles');

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
            'roles',
            'pendingSearch',
            'activeSearch',
            'activeRoleFilter'
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
            ]);

            Log::info('Aprobando usuario', [
                'user_id'       => $user->id,
                'user_email'    => $user->email,
                'role'          => $validated['role'],
                'before_active' => $user->is_active,
            ]);

            DB::beginTransaction();

            $user->is_active = true;
            $user->save();

            $user->syncRoles([$validated['role']]);
            $user->refresh()->load('roles');

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
                'role'      => 'required|exists:roles,name',
                'is_active' => 'required|boolean',
            ]);

            Log::info('Actualizando usuario', [
                'user_id'       => $user->id,
                'old_roles'     => $user->roles->pluck('name')->toArray(),
                'new_role'      => $validated['role'],
                'old_is_active' => $user->is_active,
                'new_is_active' => $validated['is_active'],
            ]);

            DB::beginTransaction();

            $user->is_active = $validated['is_active'];
            $user->save();

            $user->syncRoles([$validated['role']]);
            $user->refresh()->load('roles');

            DB::commit();

            $statusMessage = $validated['is_active'] ? 'activado' : 'desactivado';

            return response()->json([
                'success' => true,
                'message' => "Usuario {$user->name} actualizado correctamente. Estado: {$statusMessage}.",
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
        try {
            if (!auth()->user() || !auth()->user()->hasRole('superadmin')) {
                return response()->json(['error' => 'No autorizado'], 403);
            }

            Log::info('Desactivando usuario', [
                'user_id'    => $user->id,
                'user_email' => $user->email,
            ]);

            $user->is_active = false;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Usuario {$user->name} desactivado correctamente.",
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error al desactivar usuario', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'error' => 'Error al desactivar usuario: ' . $e->getMessage(),
            ], 500);
        }
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
}

