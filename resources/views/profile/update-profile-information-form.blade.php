@php
    $profileState = [
        'first_name' => old('first_name', $user->first_name),
        'middle_name' => old('middle_name', $user->middle_name),
        'first_surname' => old('first_surname', $user->first_surname),
        'second_surname' => old('second_surname', $user->second_surname),
        'gender' => old('gender', $user->gender),
        'email' => old('email', $user->email),
        'phone' => old('phone', $user->phone),
        'mobile' => old('mobile', $user->mobile),
        'phone_extension' => old('phone_extension', $user->phone_extension),

        'notify_in_app' => (bool) old('notify_in_app', $user->notify_in_app ?? true),

        'theme' => old('theme', $user->theme ?? 'system'),
        'compact_mode' => (bool) old('compact_mode', $user->compact_mode ?? false),
    ];
@endphp

<section class="theme-text">
    <div x-data="{
            loading: false,

            initial: @js($profileState),
            current: @js($profileState),

            themeSaving: false,
            themeStatus: '',

            get hasChanges() {
                return Object.keys(this.initial).some((key) => {
                    const initialValue = this.initial[key] ?? '';
                    const currentValue = this.current[key] ?? '';
                    return initialValue !== currentValue;
                });
            },

            initThemeListener() {
                // 1) Normaliza y aplica el tema inicial (fuente de verdad: window.theme.current() o current.theme)
                const initial = (window.theme?.normalize?.(window.theme?.current?.() ?? this.current.theme)) ?? this.current.theme;
                this.current.theme = initial;
                window.theme?.apply?.(initial);

                // 2) Mantiene sincronizado cuando otro componente (toggle) cambia el tema
                this.__themeChangedHandler = (event) => {
                    const incoming = window.theme?.normalize?.(event?.detail?.theme ?? window.theme?.current?.());
                    if (!incoming) return;

                    if (incoming !== this.current.theme) {
                        this.current.theme = incoming;
                    }
                };

                window.addEventListener('theme:changed', this.__themeChangedHandler);
            },

            setThemeFromSelect() {
                const next = window.theme?.normalize?.(this.current.theme) ?? this.current.theme;
                this.current.theme = next;

                // Aplica inmediato en UI
                window.theme?.apply?.(next);

                // Persiste (DB si existe; si no, local)
                this.persistTheme(next);
            },

            async persistTheme(themeValue = null) {
                const nextTheme = window.theme?.normalize?.(themeValue ?? this.current.theme) ?? (themeValue ?? this.current.theme);

                // Si no existe persist (por lo que sea), se queda en local (apply ya guardó localStorage)
                if (typeof window.theme?.persist !== 'function') {
                    this.themeStatus = 'Guardado (local)';
                    setTimeout(() => (this.themeStatus = ''), 2500);
                    return;
                }

                this.themeSaving = true;
                this.themeStatus = '';

                let result;
                try {
                    result = await window.theme.persist(nextTheme);
                } catch (error) {
                    result = {
                        ok: false,
                        theme: nextTheme,
                        persisted: 'local',
                        error: error?.message ?? 'unknown',
                    };
                } finally {
                    this.themeSaving = false;
                }

                if (result?.ok) {
                    const saved = window.theme?.normalize?.(result.theme) ?? result.theme;
                    this.current.theme = saved;
                    window.theme?.apply?.(saved);

                    // Notifica al resto de la app (toggle/otros)
                    window.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme: saved } }));

                    this.themeStatus = result.persisted === 'db' ? 'Guardado' : 'Guardado (local)';
                } else {
                    // Si falló backend, igual aplicamos local para que no se sienta roto
                    window.theme?.apply?.(nextTheme);
                    window.dispatchEvent(new CustomEvent('theme:changed', { detail: { theme: nextTheme } }));
                    this.themeStatus = 'Error';
                }

                setTimeout(() => (this.themeStatus = ''), 2500);
            },
        }" x-init="initThemeListener()">
        <form id="send-verification" method="post" action="{{ route('verification.send') }}">
            @csrf
        </form>

        <div class="max-w-5xl mx-auto space-y-6 md:space-y-8">
            <form method="post" action="{{ route('profile.update') }}" class="space-y-6 md:space-y-8"
                @submit="loading = true">
                @csrf
                @method('patch')

                @include('profile.partials.sections.basic-info')

                {{-- IMPORTANTE: en el select de Tema, usa x-model="current.theme" y @change="setThemeFromSelect()" --}}
                @include('profile.partials.sections.preferences')

                @include('profile.partials.sections.profile-actions')
            </form>
        </div>
    </div>
</section>

@push('scripts')
    @include('profile.partials.sections.profile-scripts')
@endpush
