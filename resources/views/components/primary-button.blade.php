<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-orange-red border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-deep-blue focus:bg-deep-blue active:bg-deep-blue focus:outline-none focus:ring-2 focus:ring-orange-red focus:ring-offset-2 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
