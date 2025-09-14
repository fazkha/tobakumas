<a
    {{ $attributes->merge(['href' => '#', 'target' => '_self', 'class' => 'inline-flex items-center justify-center shadow-md rounded-lg text-xs px-5 py-2.5 text-center font-semibold uppercase tracking-widest text-white focus:ring-4 focus:outline-none focus:ring-blue-300 bg-primary hover:bg-primary-700 dark:bg-primary dark:hover:bg-primary-700 dark:focus:ring-blue-800 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
