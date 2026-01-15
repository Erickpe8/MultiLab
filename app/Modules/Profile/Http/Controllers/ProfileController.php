<?php

namespace App\Modules\Profile\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordUpdateRequest;
use App\Http\Requests\ProfileAvatarUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
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

        $avatarFile = $request->file('avatar');
        $previousPhoto = $user->profile_photo_path;

        if ($avatarFile) {
            $user->profile_photo_path = $this->storeAvatarFile($avatarFile, $previousPhoto, $user->getKey());
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Update only the user's avatar.
     */
    public function updateAvatar(ProfileAvatarUpdateRequest $request): RedirectResponse|JsonResponse
    {
        $user = auth()->user();
        $avatarFile = $request->file('avatar');
        $previousPhoto = $user->profile_photo_path;

        if (! $avatarFile) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibió ninguna imagen.',
                ], 422);
            }

            return Redirect::route('profile.edit');
        }

        try {
            $path = $this->storeAvatarFile($avatarFile, $previousPhoto, $user->getKey());
        } catch (ValidationException $exception) {
            throw $exception;
        } catch (\Throwable $exception) {
            Log::error('Error updateAvatar', [
                'user_id' => $user->getKey(),
                'error' => $exception->getMessage(),
            ]);

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se pudo guardar la foto de perfil. Intenta de nuevo más tarde.',
                ], 500);
            }

            throw $exception;
        }

        $user->forceFill([
            'profile_photo_path' => $path,
        ])->save();

        $payload = [
            'success' => true,
            'path' => $path,
            'url' => Storage::disk('public')->url($path),
        ];

        if ($request->wantsJson()) {
            return response()->json($payload);
        }

        return Redirect::route('profile.edit')->with('success', 'Foto de perfil actualizada correctamente.');
    }

    private function storeAvatarFile(UploadedFile $avatarFile, ?string $previousPhoto, int $userId): string
    {
        try {
            $path = $avatarFile->store('avatars', 'public');
        } catch (\Throwable $exception) {
            Log::error('Error saving profile avatar', [
                'user_id' => $userId,
                'error' => $exception->getMessage(),
            ]);

            throw ValidationException::withMessages([
                'avatar' => 'No se pudo guardar la imagen. Intenta de nuevo o verifica el almacenamiento.',
            ]);
        }

        // FIX: cubre false / null / vacío
        if (! $path) {
            throw ValidationException::withMessages([
                'avatar' => 'No se pudo procesar la imagen.',
            ]);
        }

        $this->deletePreviousAvatar($previousPhoto, $path);

        return $path;
    }

    private function deletePreviousAvatar(?string $previousPhoto, string $currentPath): void
    {
        if (
            $previousPhoto
            && $previousPhoto !== $currentPath
            && Storage::disk('public')->exists($previousPhoto)
        ) {
            Storage::disk('public')->delete($previousPhoto);
        }
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
