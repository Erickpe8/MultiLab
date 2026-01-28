<x-layouts.guest-auth title="Verificar correo electrónico" subtitle="Confirma tu dirección antes de continuar">
    @php
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[var(--primary)] hover:bg-[var(--primary-600)] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[var(--primary)]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <x-auth-session-status class="text-sm text-center text-[var(--primary)]" :status="session('status')" />

    <p class="text-sm text-center text-[color:var(--text-muted)]">
        Gracias por registrarte. Antes de continuar, verifica tu correo electrónico haciendo clic en el enlace que te enviamos. Si no lo recibiste, podemos enviarte otro.
    </p>

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="{{ $buttonClass }}">
                Reenviar correo de verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}"
              onsubmit="localStorage.clear(); sessionStorage.clear(); localStorage.setItem('theme', 'light'); document.documentElement.dataset.theme = 'light'; document.documentElement.classList.remove('dark');">
            @csrf
            <button type="submit" class="w-full h-11 rounded-xl border border-[var(--border)] text-sm font-semibold text-[var(--primary)] hover:underline hover:border-[var(--primary)] transition">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-layouts.guest-auth>
