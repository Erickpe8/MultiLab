<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        // Solo Superadmin puede entrar aquí (por seguridad)
        $this->middleware(['auth', 'role:superadmin']);
    }

    /**
     * Mostrar listado de usuarios.
     */
    public function index()
    {
        $users = User::with('roles')
            ->orderBy('first_name')
            ->orderBy('first_surname')
            ->get();

        return view('users.index', compact('users'));
    }

    /**
     * Mostrar formulario de creación.
     */
    public function create()
    {
        // Solo los roles reales de Multilab
        $roles = Role::whereIn('name', [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ])->get();

        return view('users.create', compact('roles'));
    }

    /**
     * Guardar nuevo usuario con rol asignado.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  => ['required', 'min:8', 'confirmed'],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user = new User();
        // Dispara tu mutator de `name` que separa first_name / apellidos
        $user->name      = $validated['name'];
        $user->email     = $validated['email'];
        $user->password  = Hash::make($validated['password']);
        $user->is_active = $validated['is_active'] ?? false;
        $user->save();

        $user->assignRole($validated['role']);

        return redirect()
            ->route('users.index')
            ->with('notify', [
                'type'    => 'success',
                'message' => 'Usuario creado correctamente.',
            ]);
    }

    /**
     * Editar usuario existente.
     */
    public function edit(User $user)
    {
        $roles = Role::whereIn('name', [
            'superadmin',
            'aux_admin',
            'docente',
            'estudiante',
        ])->get();

        return view('users.edit', compact('user', 'roles'));
    }

    /**
     * Actualizar usuario y su rol.
     */
    public function update(Request $request, User $user)
    {
        // Nadie puede editar a un superadmin excepto él mismo
        if ($user->hasRole('superadmin') && Auth::id() !== $user->id) {
            abort(403, 'No tienes permisos para editar este usuario.');
        }

        $validated = $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'password'  => ['nullable', 'min:8', 'confirmed'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (array_key_exists('is_active', $validated)) {
            $user->is_active = (bool) $validated['is_active'];
        }

        $user->save();

        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('users.index')
            ->with('notify', [
                'type'    => 'success',
                'message' => 'Usuario actualizado correctamente.',
            ]);
    }

    /**
     * Eliminar usuario.
     */
    public function destroy(User $user)
    {
        // No permitir eliminar superadmin
        if ($user->hasRole('superadmin')) {
            return redirect()
                ->route('users.index')
                ->with('notify', [
                    'type'    => 'error',
                    'message' => 'No es posible eliminar un usuario con rol Superadmin.',
                ]);
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('notify', [
                'type'    => 'success',
                'message' => 'Usuario eliminado correctamente.',
            ]);
    }
}
