<header
    class="fixed inset-x-0 top-0 z-40 h-20 shrink-0 border-b border-[var(--border)]
           bg-white/90 dark:bg-[#0f1115]/90 backdrop-blur-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6">
        <div class="flex h-16 items-center justify-between">
            <a href="{{ route('welcome') }}" class="flex items-center gap-3">
                <img src="{{ asset('images/FESC-30.png') }}" alt="FESC logo" class="h-10 w-auto" />
                <span class="text-lg sm:text-xl font-bold tracking-wide text-[var(--accent)]">
                    MultiLab
                </span>
            </a>

            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 rounded-full text-sm font-semibold border border-[var(--border)]
                               bg-[var(--card)] text-[var(--text)] hover:shadow hover:border-[var(--accent)]
                               transition duration-150">
                        Iniciar sesión
                    </a>
                @endif
            </div>
        </div>
    </div>
</header>
