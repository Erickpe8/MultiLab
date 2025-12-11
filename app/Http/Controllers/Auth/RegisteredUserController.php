<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar vista de registro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesar registro de estudiantes.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name'     => ['required', 'string', 'max:255'],
            'middle_name'    => ['nullable', 'string', 'max:255'],
            'first_surname'  => ['required', 'string', 'max:255'],
            'second_surname' => ['nullable', 'string', 'max:255'],
            'email'          => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'       => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Crear usuario
        $user = User::create([
            'first_name'     => $request->first_name,
            'middle_name'    => $request->middle_name,
            'first_surname'  => $request->first_surname,
            'second_surname' => $request->second_surname,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'is_active'      => true, // Los estudiantes quedan activos
        ]);

        // Asignar rol por defecto
        $user->assignRole('estudiante');

        // Evento de registro
        event(new \Illuminate\Auth\Events\Registered($user));

        // Iniciar sesión automáticamente
        Auth::login($user);

        // Redirigir al panel de estudiantes
        return redirect()->route('panel.estudiante');
    }
}