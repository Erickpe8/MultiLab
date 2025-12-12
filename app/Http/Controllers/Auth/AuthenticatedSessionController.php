<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Mostrar la vista de login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Procesar el intento de autenticación.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        $user = Auth::user();
        $displayName = $user?->first_name ?: ($user?->name ?? 'usuario');

        return redirect()->intended(route('dashboard'))
            ->with('notify', [
                'type'    => 'success',
                'message' => 'Bienvenido(a) a MultiLab, ' . $displayName . '.',
            ]);
    }

    /**
     * Cerrar sesión.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('notify', [
                'type'    => 'info',
                'message' => 'Sesión cerrada correctamente.',
            ]);
    }
}

