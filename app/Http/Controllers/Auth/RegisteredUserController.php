<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Mostrar vista de preregistro.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Procesar una solicitud de preregistro.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'email' => strtolower(trim((string) $request->input('email'))),
        ]);

        $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'regex:/^[\\p{L}\\s]+$/u'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email',
                'regex:/^[^@\\s]+@fesc\\.edu\\.co$/i',
            ],
            'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[^A-Za-z0-9]).+$/'],
        ], [
            'name.required' => 'Escribe tu nombre completo.',
            'name.min' => 'El nombre debe tener al menos 2 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'email.required' => 'Escribe tu correo institucional.',
            'email.email' => 'Ingresa un correo válido.',
            'email.unique' => 'Ya existe una cuenta con ese correo.',
            'email.regex' => 'Solo se permite el registro con correo institucional @fesc.edu.co.',
            'password.required' => 'Escribe una contraseña.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'Incluye mayúsculas, minúsculas, números y símbolos en la contraseña.',
        ]);

        // Se crea el usuario usando el mutator de nombres seccionados
        $user = new User();
        $user->name      = $request->name; // mutator se encarga de first_name / apellidos
        $user->email     = $request->email;
        $user->password  = Hash::make($request->password);
        $user->is_active = false; // queda pendiente de aprobación del laboratorio
        $user->save();

        return redirect()->route('login');
    }
}
