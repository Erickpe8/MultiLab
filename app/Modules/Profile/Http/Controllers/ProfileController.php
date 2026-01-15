<?php

namespace App\Modules\Profile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(): View
    {
        return view('profile.edit', [
            'user' => request()->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = auth()->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Update the authenticated user's password.
     */
    public function updatePassword(PasswordUpdateRequest $request): RedirectResponse
    {
        auth()->user()->forceFill([
            'password' => Hash::make($request->validated()['password']),
        ])->save();

        return Redirect::route('profile.edit')->with('success', 'Contraseña actualizada correctamente.');
    }
}
