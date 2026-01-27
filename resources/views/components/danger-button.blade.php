<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-[var(--primary)] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-[var(--primary-600)] active:bg-[var(--primary-700)] focus:outline-none focus:ring-2 focus:ring-[var(--focus)] focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
