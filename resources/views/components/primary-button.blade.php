<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex min-h-11 items-center justify-center rounded-md border border-transparent bg-brand-950 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition-colors hover:bg-brand-900 focus:bg-brand-900']) }}>
    {{ $slot }}
</button>
