<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Se crea el usuario usando el mutator de nombres seccionados
        $user = new User();
        $user->name      = $request->name; // mutator se encarga de first_name / apellidos
        $user->email     = $request->email;
        $user->password  = Hash::make($request->password);
        $user->is_active = false; // queda pendiente de aprobación del laboratorio
        $user->save();

        return redirect()
            ->route('login')
            ->with('notify', [
                'type'    => 'info',
                'message' => 'Tu preregistro en MultiLab ha sido recibido. El equipo del laboratorio validará tu cuenta antes de permitir el acceso.',
            ]);
    }
}

