<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileThemeController extends Controller
{
    private const ALLOWED_THEMES = ['system', 'light', 'dark'];

    /**
     * Actualiza el tema preferido del usuario autenticado.
     * Entradas: $request (Request) con el campo 'theme' válido entre skins permitidos.
     * Salidas: JsonResponse confirmando la nueva selección.
     */
    public function update(Request $request): JsonResponse
    {
        if (! $request->isJson()) {
            return response()->json([
                'message' => 'Se requiere un cuerpo JSON válido.',
            ], 415);
        }

        $data = $request->validate([
            'theme' => ['required', 'string', Rule::in(self::ALLOWED_THEMES)],
        ]);

        /** @var \App\Models\User $user */
        $user = $request->user();

        $user->forceFill([
            'theme' => $data['theme'],
        ])->save();

        return response()->json([
            'ok' => true,
            'theme' => $user->theme,
        ]);
    }
}
