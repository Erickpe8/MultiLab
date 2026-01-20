<x-layouts.guest-auth title="Verificar correo electrónico" subtitle="Confirma tu dirección antes de continuar">
    @php
        $buttonClass = 'w-full h-11 rounded-xl font-semibold text-white bg-[#8E1616] hover:bg-[#D84040] disabled:opacity-60 disabled:cursor-not-allowed shadow-soft transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-[#D84040]/50 focus-visible:ring-offset-[var(--bg)]';
    @endphp

    <x-auth-session-status class="text-sm text-center text-[#8E1616]" :status="session('status')" />

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

        <form method="POST" action="{{ route('logout') }}" onsubmit="localStorage.clear(); sessionStorage.clear();">
            @csrf
            <button type="submit" class="w-full h-11 rounded-xl border border-[var(--border)] text-sm font-semibold text-[#8E1616] hover:underline hover:border-[#D84040] transition">
                Cerrar sesión
            </button>
        </form>
    </div>
</x-layouts.guest-auth>
