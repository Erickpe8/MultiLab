@section('title', 'Configuración de perfil')

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-[var(--text)] leading-tight">
            Configuración de perfil
        </h2>
        <p class="mt-1 text-sm text-[var(--text-muted)]">
            Administra tu información personal y preferencias de cuenta
        </p>
    </x-slot>

    <div class="py-8 sm:py-10 bg-[var(--bg)] min-h-screen">
        <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- CARD 1: Perfil (info + preferencias + firma) --}}
            <div class="rounded-2xl bg-white border border-[color:var(--border)] shadow-sm p-6 sm:p-8">
                @include('profile.partials.update-profile-information-form', ['renderSecurity' => false])
            </div>

            {{-- CARD 2: Seguridad (contraseña + zona de peligro) --}}
            <div class="rounded-2xl bg-white border border-[color:var(--border)] shadow-sm p-6 sm:p-8 space-y-6">
                @include('profile.partials.sections.password-update')
                @include('profile.partials.sections.danger-zone')
            </div>

        </div>
    </div>
</x-app-layout>
